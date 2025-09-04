FROM ubuntu:latest
LABEL authors="serge"

ENTRYPOINT ["top", "-b"]