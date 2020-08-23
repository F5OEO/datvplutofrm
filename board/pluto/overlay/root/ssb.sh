(while true; do cat all.wav; done) | csdr convert_i16_f | csdr fir_interpolate_cc 8 \
   | csdr dsb_fc \
  | csdr bandpass_fir_fft_cc 0 0.4 0.004 | csdr fastagc_ff |csdr fir_interpolate_cc 8| csdr convert_f_i16 | ./plutotx -s 512000 -f 437e6  -b 250
