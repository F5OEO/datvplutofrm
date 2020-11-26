#!/bin/sh
 


  pluto_temp=$(iio_attr  -c ad9361-phy temp0 input)
  pluto=$(awk "BEGIN {printf(\"%.1f\", ${pluto_temp} / 1000)}")

  xadc_raw=$(iio_attr  -c xadc temp0 raw)
  xadc_offset=$(iio_attr -c xadc temp0 offset)
  xadc_scale=$(iio_attr  -c xadc temp0 scale)
  xadc=$(awk "BEGIN {printf(\"%.1f\", -20 + (${xadc_raw} + ${xadc_offset}) * ${xadc_scale}/1000)}")

  echo "${pluto}°C - ${xadc}°C"

