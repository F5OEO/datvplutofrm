(while true; do cat all.wav; done) | csdr convert_i16_f | csdr gain_ff 1.0|csdr fmmod_fc |\
csdr fir_interpolate_cc 8|csdr gain_ff 8.0|csdr fir_interpolate_cc 8| csdr convert_f_i16 | ./plutotx -s 512000 -f 437e6  -b 250
