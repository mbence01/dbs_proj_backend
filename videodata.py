from os import path
import subprocess
import cv2 as cv
import argparse

parser = argparse.ArgumentParser()
parser.version = '1.0'
parser.add_argument('-create_thumbnail', action='store_true', default=False)
parser.add_argument('-get_duration', action='store_true', default=False)
parser.add_argument('-o', action='store', default=path.join('videostorage', 'tmp')) # TODO: Change path
parser.add_argument('-f', action='store', default=None)
args = parser.parse_args()

def main():
    if args.f is None:
        raise Exception('Filename required')

    if args.create_thumbnail:
        output = path.join(args.o, args.f + '.jpg')
        subprocess.run('ffmpeg -i "' + args.f + '" -ss 10 -vframes 1 -s 1920x1080 -y "' + output + '"', stdout = subprocess.DEVNULL, stderr = subprocess.STDOUT)
        print(output)

    if args.get_duration:
        video = cv.VideoCapture(args.f)

        fps     = video.get(cv.CAP_PROP_FPS)
        frames  = video.get(cv.CAP_PROP_FRAME_COUNT)

        print(int(frames//fps))

        video.release()

if __name__ == '__main__':
    main()
