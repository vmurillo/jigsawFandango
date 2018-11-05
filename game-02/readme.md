# Game 02

## Context

There is a service named YOLO which calls syncronously an API named WTF. WTF tends to be very unstable, having peaks in latency now and then making its responses ending up in timeouts. The high frecuency of timeouts eventually overloads YOLO's servers causing them to unexpectedly go down.

Se tiene un servicio llamado YOLO que consume síncronamente un api llamado WTF. WTF tiende a ser inestable, incrementando la latencia en sus respuestas, las cuales podrían resultar en timeouts en las peticiones que hace YOLO. La acumulación prolongada de timeouts eventualmente satura a los servidores de YOLO, pudiendo provocar caidas inesperadas en YOLO. 

## Constraints

- YOLO only waits up to 30 seconds for each call made to WTF.
- YOLO's server has a limited capacity to attend 10 request simultaneously.
- YOLO has at least 5 requests per second.
- YOLO is using the best server there is in the market.
- YOLO needs to make syncronous calls to WTF in each request.

- Yolo solo espera hasta 30 segundos por petición hacia WTF.
- el servidor de yolo solo tiene una capacidad limitada para atender a 10 peticiones en simultaneo.
- el servicio YOLO tiene un tráfico constante de 5 peticiones por 1 segundo.
- YOLO está utilizando el servidor más potente que existe en el mercado.
- YOLO necesita hacer peticiones en tiempo real a WTF.

## Problem

You are required to design an architecture using design patters which allow YOLO understand that WTF is having problems, so that, YOLO does not issue calls to WTF until WTF is available again.