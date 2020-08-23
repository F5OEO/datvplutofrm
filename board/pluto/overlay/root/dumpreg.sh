search_dir="/sys/bus/iio/devices/iio:device1"
for entry in "$search_dir"/*
do	
  value=$(cat "$entry")	
  printf "$entry -> $value\n"
done

search_dir="/sys/kernel/debug/iio/iio:device1"
for entry in "$search_dir"/*
do
  value=$(cat "$entry")
  printf "$entry -> $value\n"
done

