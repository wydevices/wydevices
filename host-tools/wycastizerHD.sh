mencoder $1 -ovc xvid -oac mp3lame -xvidencopts pass=1 -o /tmp/out.avi
ffmpeg -i /tmp/out.avi -s 640x480 -an -vcodec libx264 -vpre normal -pass 1 -padleft 26 -padright 26 -padtop 14 -padbottom 14 $2

