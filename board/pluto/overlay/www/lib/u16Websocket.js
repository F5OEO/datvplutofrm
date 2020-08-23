function u16Websocket(_websocket_url, _websocket_name, _websocket_buffer)
{
  this.ws_url = _websocket_url;
  this.ws_name = _websocket_name;
  this.ws_buffer = _websocket_buffer;

  this.ws_sock = null;
  this.ws_reconnect = null;
  this.ws_data = null;

  this.connect = function()
  {
    if (typeof MozWebSocket != "undefined")
    {
      this.ws_sock = new MozWebSocket(this.ws_url, this.ws_name);
    }
    else
    {
      this.ws_sock = new WebSocket(this.ws_url, this.ws_name);
    }
    this.ws_sock.binaryType = 'arraybuffer';
    this.ws_sock.onopen = this.onopen.bind(this);
    this.ws_sock.onmessage = this.onmessage.bind(this);
    this.ws_sock.onclose = this.onclose.bind(this);
  }
  this.onopen = function()
  {
    window.clearInterval(this.ws_reconnect);
    this.ws_reconnect = null;
  }
  this.onmessage = function(msg)
  {
    try
    {
      this.ws_data = new Uint16Array(msg.data);
      if(this.ws_data != null)
      {
        this.ws_buffer.push(this.ws_data);
      }
    }
    catch(e)
    {
      console.log("Error parsing binary!",e);
    }
  }
  this.onclose = function()
  {
    if(this.ws_sock != null)
    {
      this.ws_sock.close();
    }
    this.ws_sock = null;
    
    if(!this.ws_reconnect)
    {
      this.ws_reconnect = setInterval(function()
      {
        this.connect();
      }.bind(this),500);
    }
  }
  this.changeName = function(_newName)
  {
    this.ws_name = _newName;
    if(this.ws_sock != null)
    {
      this.ws_sock.close();
    }
  }

  if("WebSocket" in window)
  { 
    this.connect();
  }
  else
  {
    alert("Websockets are not supported in your browser!");
  }
}
