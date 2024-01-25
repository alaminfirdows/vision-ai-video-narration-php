<?php

namespace VisionAi;

use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use OpenAI;

class Application
{
    private $basePath;

    private $openAi;

    public function __construct($basePath)
    {
        $this->basePath = $basePath;
        $this->openAi = OpenAI::client('');
    }

    public function run()
    {
        $videoPath = $this->basePath . "/data/github.mp4";

        $inputDirectory = $this->basePath . "/data/";
        $outputDirectory = $this->basePath . "/output/";

        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($videoPath);

        $video->filters()->resize(new \FFMpeg\Coordinate\Dimension(640, 480))->synchronize();

        $duration = $video->getFormat()->get('duration');

        $base64Frames = [];
        for ($seconds = 0; $seconds < $duration; $seconds += 2) {
            $frame = $video->frame(TimeCode::fromSeconds($seconds));
            $imageData = $frame->save('', false, true);

            $base64Frames[] = base64_encode((string) $imageData);
        }

        $videoTitle = "How to create a GitHub repository";

        $response = $this->openAi->chat()->create([
            'model' => 'gpt-4-vision-preview',
            "max_tokens" => 50,
            "messages" => [
                [
                    "role" => "user",
                    "content" => [
                        "These are frames of a video. Where I'm making a short video tutorial, for the topic '{$videoTitle}'. The video duration is {$duration}. Create a short voiceover script in the style of David Attenborough. Only include the narration. don't make it too long.",
                        "These are frames of a video. What do you think the video is about? make a short voiceover script for 5 seconds. Only include the narration. don't make it too long.",
                        ...array_map(function ($frame) {
                            return ["image" => $frame, "resize" => 768];
                        }, $base64Frames),
                    ],
                ],
            ]
        ]);

        $narration = $response->choices[0]->message->content;
        // "In today's tutorial, we're diving into the process of creating and managing a new repository, a fundamental skill for collaborative programming and version control.";

        $audio = $this->openAi->audio()->speech([
            'model' => 'tts-1-1106',
            "voice" => "onyx",
            "input" => $narration,
        ]);

        $advancedMedia = $ffmpeg->openAdvanced([
            $inputDirectory . "github.mp4",
            $inputDirectory . "github.mp3",
        ]);

        $advancedMedia
            ->map(array('0:a', '1:v'), new X264(), $outputDirectory . 'output.mp4')
            ->save();
    }
}
