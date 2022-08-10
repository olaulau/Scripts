#!/bin/bash

nice -n 19 ionice -c idle $@

