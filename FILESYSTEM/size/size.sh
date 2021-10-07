#!/bin/bash

du \
	--block-size=G \
	--threshold=10G \
	--one-file-system \
. \
2> /dev/null

