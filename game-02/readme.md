# Game 02

## Context

There is a service named YOLO which calls synchronously an API named WTF. WTF tends to be very unstable, having peaks in latency now and then making its responses ending up in timeouts. The high frequency of timeouts eventually overloads YOLO's servers causing them to unexpectedly go down. 

## Constraints

- YOLO only waits up to 30 seconds for each call made to WTF.
- YOLO's server has a limited capacity to attend 10 request simultaneously.
- YOLO has at least 5 requests per second.
- YOLO is using the best server there is in the market.
- YOLO needs to make synchronous calls to WTF in each request.

## Challenge

You are required to design an architecture using design patters which allow YOLO understand that WTF is having problems, so that, YOLO does not issue calls to WTF until WTF is available again.
