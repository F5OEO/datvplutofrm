# About MQTT in PlutoDVB

MQTT stands for Message Queuing Telemetry Transport. It is a lightweight publish and subscribe system where you can publish and receive messages as a client. MQTT is a simple messaging protocol, designed for constrained devices with low-bandwidth. So, it's the perfect solution for Internet of Things applications.

MQTT is implemented in PlutoDVB for instant communication needs of the human-machine interface with the Pluto core. A MQTT mosquitto brocker is implemented, communicating by websocket ```9001``` or directly on ```8883``` port.

This allows the parameters changed by the operator to be taken into account without delay, on the fly.

## Implemented messages 

- ```plutodvb/var``` All the variables available on a page are sent in the following form ``` { "id or name" : value }```
	-  ```'{"fec":"13"}``` Ask to change FEC to 1/3
	-  ```'{"ptt":"false"}``` Ask to stop transmission
	-  ```'{"ptt":"true"}``` Ask to go to transmission	
	-  ```'{"page":"setup.php"}``` Tell the user is (re)load this page, or client is reconnected
	-  ```'{"file_firm":"C:\fakepath\pluto.frm"}``` A firmware file is uploaded	
- ```plutodvb/started``` Sended by the PlutoDVB when is started
- ```plutodvb/ts/netbitrate``` Transport Stream Bitrate in bits/s

"file_firm":"C:\fakepath\pluto.frm"}



# Feedbacks and discussions
https://groups.io/g/plutodvb
