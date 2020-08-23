echo "unbind FPGA"
echo 79020000.cf-ad9361-lpc > /sys/bus/platform/drivers/cf_axi_adc/unbind
echo 79024000.cf-ad9361-dds-core-lpc > /sys/bus/platform/drivers/cf_axi_dds/unbind
echo 7c400000.dma > /sys/bus/platform/drivers/dma-axi-dmac/unbind
echo 7c420000.dma > /sys/bus/platform/drivers/dma-axi-dmac/unbind

echo Flash bitstream $1
echo 0 > /sys/class/fpga_manager/fpga0/flags
cp $1 /lib/firmware
echo $1 > /sys/class/fpga_manager/fpga0/firmware
sleep 1

echo "bind FPGA"
echo 7c420000.dma > /sys/bus/platform/drivers/dma-axi-dmac/bind
echo 7c400000.dma > /sys/bus/platform/drivers/dma-axi-dmac/bind
echo 79024000.cf-ad9361-dds-core-lpc > /sys/bus/platform/drivers/cf_axi_dds/bind
echo 79020000.cf-ad9361-lpc > /sys/bus/platform/drivers/cf_axi_adc/bind

echo restart iiod
/etc/init.d/S99iiod restart



