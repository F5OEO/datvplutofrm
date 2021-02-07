  if (!window.navigator.onLine) 
  { 
   //no execution (exit)
  }

  else
  {

var ws_url = "wss://eshail.batc.org.uk/wb/fft";
var ws_name = 'fft_f5oeoplutofw';

if(typeof ws_url_override !== 'undefined')
{
  ws_url = ws_url_override;
}

var render_timer;

const render_interval_map = {
  'fft': 250, // ms
  'fft_fast': 100 // ms
};

var render_interval = render_interval_map[ws_name];
var render_busy = false;
var render_buffer = [];

var el;
var canvas_jqel;
var ctx;
var canvasWidth;
var canvasHeight;
var signal_selected = null; //if not undefined, shows box on channel clicked
var signal_tune = null;     //if not undefined tune to receiver

var mouse_in_canvas = false;
var mouse_x = 0;
var mouse_y = 0;
var clicked_x = 0;
var clicked_y = 0;

var beacon_strength = 0;

var fft_colour = '#ee7300';

var signals = []; 
var freq_info = [];

/* Load vars from local storage */
if(typeof(Storage) !== "undefined")
{
  storageSupport = true;

  if(localStorage.wb_fft_colour)
  {
    fft_colour = localStorage.wb_fft_colour;
  }

  if(localStorage.wb_fft_speed)
  {
    ws_name = localStorage.wb_fft_speed;
    render_interval = render_interval_map[ws_name];
  }
}


/* On load */
$(function() {
  if (!window.navigator.onLine) 
  { 
    return false;
  }
  else {
  var fft_ws = new u16Websocket(ws_url, ws_name, render_buffer);
  //console.log(fft_ws);
  canvasHeight = 550;
  canvasWidth = $("#fft-col").width(); //padding
  
  el = document.getElementById('c');
  canvas_jqel = $("#c");

  initCanvas();

  updateFFT(null);
 
 }
 


  canvas_jqel.on('mousemove', function(e)
  {
    mouse_in_canvas = true;

    const el_boundingRectangle = el.getBoundingClientRect();
    mouse_x = e.clientX - el_boundingRectangle.left;
    mouse_y = e.clientY - el_boundingRectangle.top;

    render_frequency_info(mouse_x, mouse_y);

    render_signal_box(mouse_x, mouse_y);

    if(typeof signal_selected !== 'undefined')
    {
      render_signal_selected_box(clicked_x, clicked_y);
    }
  });

  canvas_jqel.on('mouseleave', function(e)
  {
    mouse_in_canvas = false;
  });

  canvas_jqel.on('click', function(e)
  {

    const el_boundingRectangle = el.getBoundingClientRect();
    clicked_x = e.clientX - el_boundingRectangle.left;
    clicked_y = e.clientY - el_boundingRectangle.top;

    copy_upfreq(clicked_x, clicked_y);

    if(typeof signal_selected !== 'undefined')
    {
      render_signal_selected_box(clicked_x, clicked_y);

      if(signal_selected != null && typeof signal_tune !== 'undefined')
      {
        if(mouse_y <= (canvasHeight * 7/8)) {
        signal_steering(signal_selected.frequency, signal_selected.symbolrate);
        }
      } 
    }       
  });
});

function signal_steering(f,sr) { //F5UII 2608
 
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        //document.getElementsByName("power")[0].value = this.responseText;
      }
    };
    let receiver = 'minitiouner';
    f = parseFloat(f) - 10000;
    xmlhttp.open("GET", "receiver.php?f="+f.toFixed(3)+'&rate='+(sr/1000)+'&rec='+receiver, true);
    xmlhttp.send();
          $('#message_spectrum').html('Receiver channel change request completed !');
          $("#message_spectrum").fadeIn(250).fadeOut(1500);
        
  }

function initCanvas()
{
  $("#c").attr( "width", canvasWidth );
  $("#c").attr( "height", canvasHeight );

  ctx = el.getContext('2d');

  devicePixelRatio = window.devicePixelRatio || 1,
  backingStoreRatio = ctx.webkitBackingStorePixelRatio ||
                      ctx.mozBackingStorePixelRatio ||
                      ctx.msBackingStorePixelRatio ||
                      ctx.oBackingStorePixelRatio ||
                      ctx.backingStorePixelRatio || 1,
  ratio = devicePixelRatio / backingStoreRatio;

  if (devicePixelRatio !== backingStoreRatio)
  {
    var oldWidth = el.width;
    var oldHeight = el.height;

    el.width = oldWidth * ratio;
    el.height = oldHeight * ratio;

    el.style.width = oldWidth + 'px';
    el.style.height = oldHeight + 'px';

    ctx.scale(ratio, ratio);
  }  
}

function updateFFT(data)
{
  var i;

  const _start_freq = 490.5;

  /* Clear Screen */
  ctx.clearRect(0, 0, canvasWidth, canvasHeight);
  ctx.save(); 
  
  /* Draw Dashed Vertical Lines and headers */
  ctx.lineWidth = 1;
  ctx.strokeStyle = 'grey';
  ctx.setLineDash([5, 20]);
  ctx.font = "13px 'Pathway Gothic One', sans-serif";
  ctx.fillStyle = "#636363";
  ctx.textAlign = "center";
  for(i=0;i<18;i+=2)
  {
    /* Draw vertical line */
    ctx.beginPath();
    ctx.moveTo((canvasWidth/18)+i*(canvasWidth/18),25);
    ctx.lineTo((canvasWidth/18)+i*(canvasWidth/18),canvasHeight*(7/8));
    ctx.stroke();
    /* Draw Vertical Text */
    ctx.fillText("10.4"+(91+(i*0.5)),(canvasWidth/18)+i*(canvasWidth/18),17);
  }

  /* Draw Horizontal Lines */
  ctx.lineWidth = 1;
  ctx.strokeStyle = 'grey';
  ctx.setLineDash([5, 10]);
  ctx.font = "12px 'Pathway Gothic One', sans-serif";
  ctx.fillStyle = "#636363";
  ctx.textAlign = "center";
  for(i=1;i<=4;i++)
  {
    linePos = (i*(canvasHeight/4))-(canvasHeight/6);
    ctx.beginPath();
    ctx.moveTo(0+35, linePos);
    ctx.lineTo(canvasWidth-35, linePos);
    ctx.stroke();
    /* Annotate lines above 0dB */
    if(i!=4)
    {
      ctx.fillText((5*(4-i))+"dB",17,linePos+4);
      ctx.fillText((5*(4-i))+"dB",canvasWidth-17,linePos+4);
    }
  }

  /* Draw Minor Horizontal Lines */
  ctx.lineWidth = 1;
  ctx.strokeStyle = 'grey';
  ctx.setLineDash([1, 10]);
  for(i=1;i<20;i++)
  {
    if(i % 5 != 0)
    {
      linePos = (i*(canvasHeight/20))-(canvasHeight/6);
      ctx.beginPath();
      ctx.moveTo(0+10, linePos);
      ctx.lineTo(canvasWidth-10, linePos);
      ctx.stroke();
    }
  }

  ctx.restore();

  /* Draw Band Splits */
  ctx.lineWidth = 1;
  ctx.strokeStyle = 'grey';

  function draw_divider(frequency, height)
  {
    ctx.beginPath();
    ctx.moveTo((frequency-_start_freq)*(canvasWidth/9),canvasHeight*height);
    ctx.lineTo((frequency-_start_freq)*(canvasWidth/9),canvasHeight*(7.9/8));
    ctx.stroke();
  }

  /* Beacon & Simplex / Simplex */
  draw_divider(492.5, (7.1/8.0));

  /* Simplex / RB-TV */
  draw_divider(497.0, (7.325/8.0));

  /* Draw channel allocations */
  ctx.fillStyle = '#33b3ca';

  function draw_channel(center_frequency, bandwidth, line_height)
  {
    const rolloff = 1.35 / 2.0;

    if(typeof freq_info !== 'undefined') {

      if (freq_info.length == 44) freq_info = []; // hack to avoid continued push(). better to precompute all points and draw.
      freq_info.push({x1: ((center_frequency-(rolloff*bandwidth))-_start_freq)*(canvasWidth/9), x2: ((center_frequency+(rolloff*bandwidth))-_start_freq)*(canvasWidth/9), y: canvasHeight*line_height, center_frequency: center_frequency, bandwidth:bandwidth});
    }

    ctx.fillRect(((center_frequency-(rolloff*bandwidth))-_start_freq)*(canvasWidth/9), canvasHeight*line_height, 2*(rolloff*bandwidth)*(canvasWidth/9), 5);
  }

  /* 1MS */
  for(var f=493.25; f<=496.25; f=f+1.5)
  {
    draw_channel(f, 1.0, (7.475/8));
  }

  /* 333Ks */
  for(var f=492.75; f<=499.25; f=f+0.5)
  {
    draw_channel(f, 0.333, (7.25/8));
  }

  /* 125Ks */
  for(var f=492.75; f<=499.25; f=f+0.25)
  {
    draw_channel(f, 0.125, (7.025/8));
  }

  ctx.restore();

  /* Annotate Bands */
  ctx.font = "16px 'Pompiere', cursive";
  ctx.fillStyle = "#636363";
  ctx.textAlign = "center";
  ctx.fillText("A71A DATV Beacon",((491.5)-_start_freq)*(canvasWidth/9),canvasHeight-45);
  ctx.fillText("10491.500",((491.5)-_start_freq)*(canvasWidth/9),canvasHeight-28);
  ctx.fillText("(1.5MS/s QPSK, 4/5)",((491.5)-_start_freq)*(canvasWidth/9),canvasHeight-12);
  ctx.fillText("Wide & Narrow DATV",((494.75)-_start_freq)*(canvasWidth/9),canvasHeight-12);
  ctx.fillText("Narrow DATV",((498.25)-_start_freq)*(canvasWidth/9),canvasHeight-12);
  ctx.restore();

  /* Draw FFT */
  if(data != null)
  {
    var start_height = canvasHeight*(7/8);
    var data_length = data.length;

    var sample;
    var sample_index;
    var sample_index_f;

    ctx.lineWidth=1;

    var grad= ctx.createLinearGradient(0, 0, 0, canvasHeight);
    grad.addColorStop(0, "red");
    grad.addColorStop(1, "#33b3ca");


    //ctx.strokeStyle = fft_colour;
    ctx.strokeStyle = grad;
    for(i=0; i<canvasWidth; i++)
    {
      sample_index = (i*data_length)/ canvasWidth;
      sample_index_f = sample_index | 0;
      sample = data[sample_index_f]
         + (sample_index - sample_index_f) * (data[sample_index_f+1] - data[sample_index_f]);
      sample = (sample/65536.0);

      if(sample > (1/8))
      {
        ctx.beginPath();
        ctx.moveTo(i, start_height);
        ctx.lineTo(i, canvasHeight-(Math.min(sample, 1.0) * canvasHeight));
        ctx.stroke();
      }
    }
    ctx.restore();
  }
  else
  {
    ctx.font = "15px 'Pathway Gothic One', sans-serif";
    ctx.fillStyle = "black";
    ctx.textAlign = "center";
    ctx.fillText("Loading..",(canvasWidth/2)+(canvasWidth/35),(3*(canvasHeight/4))-((1.1/6)*canvasHeight));
    ctx.restore();
  }
}

function draw_decoded()
{
  var i;

  for(i = 0; i < signals_decoded.length; i++)
  {
    text_x_position = (signals_decoded[i].frequency - 10490.5) * (canvasWidth / 9.0);

    /* Adjust for right-side overlap */
    if(text_x_position > (0.97 * canvasWidth))
    {
      text_x_position = canvasWidth - 55;
    }

    ctx.font = "bold 14px 'Pathway Gothic One', sans-serif";
    ctx.fillStyle = "black";
    ctx.textAlign = "center";
    ctx.fillText(
      signals_decoded[i].name,
        text_x_position,
        canvasHeight*(6.5/8)
      );
  }
  ctx.restore();
}

function render_fft()
{
  if(!render_busy)
  {
    render_busy = true;
    if(render_buffer.length > 0)
    {
      /* Pull oldest frame off the buffer and render it */
      var data_frame = render_buffer.shift();
      updateFFT(data_frame);
      detect_signals(data_frame);

      if(typeof signals_decoded !== 'undefined')
      {
        draw_decoded();
      }

      /* If we're buffering up, remove old queued frames (unsure about this) */
      if(render_buffer.length > 2)
      {
        render_buffer.splice(0, render_buffer.length - 2);
      }
    }
    render_busy = false;
  }
  else
  {
    console.log("Slow render blocking next frame, configured interval is ", render_interval);
  }
}
render_timer = setInterval(render_fft, render_interval);



function align_symbolrate(width)
{
  if(width < 0.022)
  {
    return 0;
  }

  else if(width < 0.040)
  {
    return 0.025;
  }
    else if(width < 0.059)
  {
    return 0.033;
  }
  else if(width < 0.060)
  {
    return 0.035;
  }
  else if(width < 0.086)
  {
    return 0.066;
  }
  else if(width < 0.185)
  {
    return 0.125;
  }
  else if(width < 0.277)
  {
    return 0.250;
  }
  else if(width < 0.388)
  {
    return 0.333;
  }
  else if(width < 0.700)
  {
    return 0.500;
  }
  else if(width < 1.2)
  {
    return 1.000;
  }
  else if(width < 1.6)
  {
    return 1.500;
  }
  else if(width < 2.2)
  {
    return 2.000;
  }
  else
  {
    return Math.round(width*5)/5.0;
  }
}

function print_symbolrate(symrate)
{
  if(symrate < 0.7)
  {
    return Math.round(symrate*1000)+"KS";
  }
  else
  {
    return (Math.round(symrate*10)/10)+"MS";
  }
}

function print_frequency(freq,symrate)
{
  if(symrate < 0.7)
  {
    return "'"+(Math.round(freq*80)/80.0).toFixed(3);
  }
  else
  {
    return "'"+(Math.round(freq*40)/40.0).toFixed(3);
  }
}


const scale_db = 3276.8;
function is_overpower(beacon_strength, signal_strength, signal_bw)
{
  if(beacon_strength != 0)
  {
    if(signal_bw < 0.7) // < 1MS
    {
      return false;
    }
    
    if(signal_strength > (beacon_strength - (0.75 * scale_db))) // >= 1MS
    {
      return true;
    }
  }
  return false;
}

function detect_signals(fft_data)
{
  var i;
  var j;

  const noise_level = 11000;
  const signal_threshold = 16000;

  var in_signal = false;
  var start_signal;
  var end_signal;
  var mid_signal;
  var strength_signal;
  var signal_bw;
  var signal_freq;
  var acc;
  var acc_i;

  var db_per_pixel;
  var beacon_strength_pixel;

  var text_x_position;

  /* Clear signals array */
  signals = [];

  for(i=2;i<fft_data.length;i++)
  {
    if(!in_signal)
    {
      if((fft_data[i] + fft_data[i-1] + fft_data[i-2])/3.0 > signal_threshold)
      {
        in_signal = true;
        start_signal = i;
      }
    }
    else /* in_signal == true */
    {
      if((fft_data[i] + fft_data[i-1] + fft_data[i-2])/3.0 < signal_threshold)
      {
        in_signal = false;

        end_signal = i;
        acc = 0;
        acc_i = 0;
        for(j=(start_signal + (0.3*(end_signal - start_signal))) | 0; j<start_signal+(0.7*(end_signal - start_signal)); j++)
        {
          acc = acc + fft_data[j];
          acc_i = acc_i + 1;
        }
        /*
          ctx.lineWidth=1;
          ctx.strokeStyle = 'red';
          ctx.beginPath();
          ctx.moveTo((start_signal/fft_data.length)*canvasWidth, canvasHeight * (1 - (signal_threshold/65536)));
          ctx.lineTo((end_signal/fft_data.length)*canvasWidth, canvasHeight * (1 - (signal_threshold/65536)));
          ctx.stroke();
        ctx.restore();
        */

        strength_signal = acc / acc_i;
        /*
          ctx.lineWidth=1;
          ctx.strokeStyle = 'yellow';
          ctx.beginPath();
          ctx.moveTo((start_signal/fft_data.length)*canvasWidth, canvasHeight * (1 - (strength_signal/65536)));
          ctx.lineTo((end_signal/fft_data.length)*canvasWidth, canvasHeight * (1 - (strength_signal/65536)));
          ctx.stroke();
        ctx.restore();
        */

        /* Find real start of top of signal */
        for(j = start_signal; (fft_data[j] - noise_level) < 0.75*(strength_signal - noise_level); j++)
        {
          start_signal = j;
        }
        /*
          ctx.lineWidth=1;
          ctx.strokeStyle = 'blue';
          ctx.beginPath();
          ctx.moveTo((start_signal/fft_data.length)*canvasWidth, canvasHeight * (1 - (strength_signal/65536)));
          ctx.lineTo((start_signal/fft_data.length)*canvasWidth, canvasHeight * (1 - (strength_signal/65536)) + 20);
          ctx.stroke();
        ctx.restore();
        */

        /* Find real end of the top of signal */
        for(j = end_signal; (fft_data[j] - noise_level) < 0.75*(strength_signal - noise_level); j--)
        {
          end_signal = j;
        }
        /*
          ctx.lineWidth=1;
          ctx.strokeStyle = 'blue';
          ctx.beginPath();
          ctx.moveTo((end_signal/fft_data.length)*canvasWidth, canvasHeight * (1 - (strength_signal/65536)));
          ctx.lineTo((end_signal/fft_data.length)*canvasWidth, canvasHeight * (1 - (strength_signal/65536)) + 20);
          ctx.stroke();
        ctx.restore();
        */

        mid_signal = start_signal + ((end_signal - start_signal)/2.0);

        signal_bw = align_symbolrate((end_signal - start_signal) * (9.0 / fft_data.length));
        signal_freq = 490.5 + (((mid_signal+1) / fft_data.length) * 9.0);

        signals.push(
          {
            "start": (start_signal/fft_data.length)*canvasWidth,
            "end": (end_signal/fft_data.length)*canvasWidth,
            "top": canvasHeight-((strength_signal/65536) * canvasHeight),
            "frequency": 10000 + signal_freq,
            "symbolrate": 1000.0 * signal_bw
          }
        );

        // Exclude signals in beacon band
        if(signal_freq < 492.0)
        {
          if(signal_bw >= 1.0)
          {
            // Probably the Beacon!
            beacon_strength = strength_signal;
          }
          continue;
        }

  /*
        console.log("####");
    for(j = start_signal; j < end_signal; j++)
    {
  console.log(fft_data[j]);
    }
    */

        /* Sanity check bandwidth, and exclude beacon */
        if(signal_bw != 0)
        {
          text_x_position = (mid_signal/fft_data.length)*canvasWidth;

          /* Adjust for right-side overlap */
          if(text_x_position > (0.92 * canvasWidth))
          {
            text_x_position = canvasWidth - 55;
          }

          ctx.font = "14px 'Pathway Gothic One', sans-serif";
          ctx.fillStyle = "black";
          ctx.textAlign = "center";
          if(!is_overpower(beacon_strength, strength_signal, signal_bw))
          {
            ctx.fillText(
              print_symbolrate(signal_bw)+", "+print_frequency(signal_freq,signal_bw),
              text_x_position,
              canvasHeight-((strength_signal/65536) * canvasHeight) - 16
            );
            ctx.restore();
          }
          else
          {
            ctx.fillText(
              "[over-power]",
              text_x_position,
              canvasHeight-((strength_signal/65536) * canvasHeight) - 16
            );
            ctx.restore();

            ctx.lineWidth = 2;
            ctx.strokeStyle = 'black';
            ctx.setLineDash([4, 4]);
            ctx.beginPath();
            ctx.moveTo((start_signal/fft_data.length)*canvasWidth, canvasHeight * (1 - ((beacon_strength-(1.0*scale_db))/65536)));
            ctx.lineTo((end_signal/fft_data.length)*canvasWidth, canvasHeight * (1 - ((beacon_strength-(1.0*scale_db))/65536)));
            ctx.stroke();
	          ctx.setLineDash([]);
            ctx.restore();
          }
        }
      }
    }
  }

  if(in_signal)
  {
    end_signal = fft_data.length;
    acc = 0;
    acc_i = 0;
    for(j=(start_signal + (0.3*(end_signal - start_signal))) | 0; j<start_signal+(0.7*(end_signal - start_signal)); j++)
    {
      acc = acc + fft_data[j];
      acc_i = acc_i + 1;
    }
  
    strength_signal = acc / acc_i;
  
    ctx.font = "14px 'Pathway Gothic One', sans-serif";
    ctx.fillStyle = "black";
    ctx.textAlign = "center";
    ctx.fillText(
      "[out-of-band]",
      (canvasWidth - 55),
      canvasHeight-((strength_signal/65536) * canvasHeight) - 16
    );
    ctx.restore();
  }

  if(mouse_in_canvas)
  {
    render_frequency_info(mouse_x, mouse_y);

    render_signal_box(mouse_x, mouse_y);
  }

  if(typeof signal_selected !== 'undefined' && signal_selected != null)
  {
    render_signal_selected_box(clicked_x, clicked_y);
  }
}

function render_signal_box(mouse_x, mouse_y)
{
  if(mouse_y < (canvasHeight * 7/8))
  {
    for(i=0; i<signals.length; i++)
    {
      if(mouse_x > signals[i].start
        && mouse_x < signals[i].end
        && mouse_y > signals[i].top)
      {
        ctx.lineWidth = 1;
        ctx.strokeStyle = '#1e4056';
        
	      ctx.beginPath();
        ctx.moveTo(signals[i].start, canvasHeight * (7/8));
        ctx.lineTo(signals[i].start, signals[i].top);
        ctx.stroke();
        
	      ctx.beginPath();
        ctx.moveTo(signals[i].start, signals[i].top);
        ctx.lineTo(signals[i].end, signals[i].top);
        ctx.stroke();
        
	      ctx.beginPath();
        ctx.moveTo(signals[i].end, canvasHeight * (7/8));
        ctx.lineTo(signals[i].end, signals[i].top);
        ctx.stroke();

        /* As long as we have a beacon, and for signals other than the beacon, display relative power on mouseover */
        if((beacon_strength > 0) && (signals[i].start > canvasWidth / 8))
        {
            ctx.font = (signals[i].symbolrate < 500 ? "11px" : "12px") + " 'Pathway Gothic One', sans-serif";
            ctx.fillStyle = "#1e4056";
            ctx.textAlign = "center";

            db_per_pixel = ((canvasHeight * 7/8) - (canvasHeight / 12)) / 15; // 15dB screen window
            beacon_strength_pixel  = canvasHeight - ((beacon_strength / 65536 ) * canvasHeight);

            ctx.fillText(((beacon_strength_pixel- signals[i].top) / db_per_pixel).toFixed(1) + " dBb",  
                              signals[i].start - ((signals[i].start - signals[i].end)/2),  
                              (canvasHeight * 7/8) - (7*((canvasHeight * 7/8) - signals[i].top)/8));      
        
        }  
        
	      ctx.restore();

        return;
      }
    }
  }
}

function render_signal_selected_box(mouse_clicked_x, mouse_clicked_y)
{
  if(mouse_y < (canvasHeight * 7/8))
  {
    for(i=0; i<signals.length; i++)
    {
      if(mouse_clicked_x > signals[i].start
        && mouse_clicked_x < signals[i].end
        && mouse_clicked_y > signals[i].top)
      {
        signal_selected = signals[i];

        ctx.save();
        ctx.lineWidth = 3;
        ctx.strokeStyle = 'black';
        ctx.beginPath();
        ctx.moveTo(signal_selected.start, canvasHeight * (7/8));
        ctx.lineTo(signal_selected.start, signal_selected.top);
        ctx.stroke();
        ctx.beginPath();
        ctx.moveTo(signal_selected.start, signal_selected.top);
        ctx.lineTo(signal_selected.end, signal_selected.top);
        ctx.stroke();
        ctx.beginPath();
        ctx.moveTo(signal_selected.end, canvasHeight * (7/8));
        ctx.lineTo(signal_selected.end, signal_selected.top);
        ctx.stroke();
        ctx.restore();

        return;
      }
    }
  }
}


function copy_upfreq(mouse_x, mouse_y)
{
if(mouse_y > (canvasHeight * 7/8))
  {
        
       
        //$(t+'[name="freq"]').val($("#upf").val()- $(t+'[name="trvlo"]').val());
        $(t+'[name="freq"]').val((parseFloat($("#upf").val()- $(t+'[name="trvlo"]').val()+$(t+'input[name ="finefreqtune"]').val()/1000)).toFixed(3)).change();
        $(t+'#f-central').val((parseFloat($("#upf").val()- $(t+'[name="trvlo"]').val())));
        //console.log('tab wf = '+t + ' .upf='+$("#upf").val() + ' tvrlo='+$(t+'[name="trvlo"]').val());
        save_local_modulator();
        let textarea = document.getElementById("upf");
        textarea.select();
        let ret= document.execCommand('copy');
        if ((ret == true) && (obs_ws_connected==false)) {
          let ret=false;
          $('#message_spectrum').html('Frequency set and also copied in clipboard ! <span id="rtmp"><i>Click here to copy RTMP server URL in </i>📋<i>.</i></span>');
          $("#message_spectrum").fadeIn(250).delay(5000).fadeOut(1500);
        } else {

           let t='#tab'+tab+'C ';
          //rtmp://192.168.2.1:7272/,437,DVBS2,QPSK,333,23,0,nocalib,800,32,
          let m=window.location.origin+':7272/,' + $(t+"input[name='freq']").val() + ',' + $(t+"select[name='mode']").val()+ ',' + $(t+"select[name='mod']").val() + ',' + $(t+"input[name='sr']").val() + ',' + $(t+"select[name='fec']").val() + ',' + $(t+"input[name='power']").val() + ',nocalib,'+$(t+"input[name='pcrpts']").val() +',32,';
          m = m.replace("http://", "rtmp://");

          if (obs_ws_connected==true) {
            if (typeof obsstreamurl === "function") { 
              
              obsstreamurl(m,$(t+"input[name='callsign']").val());
              $('#message_spectrum').html('The URL and key strings have just been sent directly to OBS Studio (Parameters/Stream/Custom Server).<br/> <span style="font-family:verdana;  font-size: 12px; "> '+ m +'</span>');
              $("#message_spectrum").fadeIn(250).delay(5000).fadeOut(1500);
            }
          }
        }

         $('#rtmp').click(function () {
          let t='#tab'+tab+'C ';
          //rtmp://192.168.2.1:7272/,437,DVBS2,QPSK,333,23,0,nocalib,800,32,
          let m=window.location.origin+':7272/,' + $(t+"input[name='freq']").val() + ',' + $(t+"select[name='mode']").val()+ ',' + $(t+"select[name='mod']").val() + ',' + $(t+"input[name='sr']").val() + ',' + $(t+"select[name='fec']").val() + ',' + $(t+"input[name='power']").val() + ',nocalib,'+$(t+"input[name='pcrpts']").val() +',32,';
          m = m.replace("http://", "rtmp://");
          document.getElementById("upf").value = m;
          let textarea = document.getElementById("upf");
          textarea.select();
          let ret= document.execCommand('copy');
          if (ret == true) {
            ret=false;
            $('#message_spectrum').html('The URL string is in the clipboard and can by paste in your streaming software.<br/> <span style="font-family:verdana;  font-size: 12px; "> '+ m +'</span>');
                   
          }

          
    
        });

}

}


function render_frequency_info(mouse_x, mouse_y)
{
  var display_triggered = false;
  if(mouse_y > (canvasHeight * 7/8))
  {
    for(var i = 0; i < freq_info.length; i ++)
    {
      xd1 = freq_info[i].x1;
      xd2 = freq_info[i].x2;
      yd  = freq_info[i].y;
      if ((mouse_x > xd1-1) && (mouse_x < xd2+1)
        &&(mouse_y > yd-5) && (mouse_y < yd+5) )
      {
        el.title = "Downlink: " + (10000.00 + freq_info[i].center_frequency) + 
                   " MHz\nUplink: " + (1910.50 + freq_info[i].center_frequency) +
                   " MHz (Click to set freq.)\nSymbol Rate: " + ((freq_info[i].bandwidth == 0.125) ? "125/66/33 Ksps" :
                    (freq_info[i].bandwidth == 0.333) ? "500/333/250 Ksps" : "1 Msps");


        ctx.fillStyle = '#1e4056';

        ctx.fillRect(xd1, yd, xd2-xd1, 5);
        display_triggered = true;
        //document.getElementById("upf").value = (1910.50 + freq_info[i].center_frequency - document.getElementsByName('trvlo')[0].value) ;
        document.getElementById("upf").value = (1910.50 + freq_info[i].center_frequency ) ;



        break;
      }
    }
  }
  if(!display_triggered)
  {
    el.title = "";
  }
}

function fft_fullscreen()
{
  if(el.requestFullscreen)
  {
    el.requestFullscreen();
  }
  else if(el.webkitRequestFullScreen)
  {
    el.webkitRequestFullScreen();
  }
  else if(el.mozRequestFullScreen)
  {
    el.mozRequestFullScreen();
  }  
}

var checkFullScreen = function()
{
  if(typeof document.fullScreen != "undefined")
  {
    return document.fullScreen;
  }
  else if(typeof document.webkitIsFullScreen != "undefined")
  {
    return document.webkitIsFullScreen;
  }
  else if(typeof document.mozFullScreen != "undefined")
  {
    return document.mozFullScreen;
  }
  else
  {
    return false;
  }
}

var previousOrientation = window.orientation;
var checkOrientation = function()
{
  if(checkFullScreen())
  {
    if(window.orientation !== previousOrientation)
    {
      if (0 != (previousOrientation + window.orientation) % 180)
      {
        canvasWidth = window.innerHeight;
        canvasHeight = window.innerWidth;
        initCanvas();
      }

      previousOrientation = window.orientation;

      previousHeight = window.innerHeight;
      previousWidth = window.innerWidth;
    }
  }
};

var previousHeight = window.innerHeight;
var previousWidth = window.innerWidth;
var checkResize = function()
{
  if(!checkFullScreen()
    && (previousHeight != window.innerHeight || previousWidth != window.innerWidth))
  {
    canvasHeight = 550;
    canvasWidth = $("#fft-col").width() ; 
    initCanvas();

    previousHeight = window.innerHeight;
    previousWidth = window.innerWidth;
  }
}

window.addEventListener("fullscreenchange", function()
{
  if(checkFullScreen())
  {
    setTimeout(function() {
      /* Set canvas to full document size */
      canvasHeight = $("#c").height();
      canvasWidth = $("#c").width();
      initCanvas();
    },10);
  }
  else
  {
    /* Reset canvas size */
    canvasHeight = 550;
    canvasWidth = $("#fft-col").width();

    initCanvas();
  }
});

window.addEventListener("resize", checkResize, false);
window.addEventListener("orientationchange", checkOrientation, false);

// Android doesn't always fire orientationChange on 180 degree turns
setInterval(checkOrientation, 2000);
}