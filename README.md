# Video Narration with OpenAI and FFmpeg

This project uses OpenAI's API to extract text information from video frames and generate voice narration. It integrates `PHP-FFmpeg` to combine the generated voice with the original video. The goal is to create a seamless narration that matches the visual content.

## Features
- **Frame Analysis**: Uses OpenAI's API to analyze video frames and extract text.
- **Text-to-Speech**: Converts extracted text into voice narration.
- **Video Editing**: Integrates `PHP-FFmpeg` to merge the generated voice with the video.
- **Synchronization**: Ensures the voice narration syncs accurately with the video content.

## Getting Started
To run this project, you'll need:
- PHP and the `PHP-FFmpeg` library installed
- Access to OpenAI's API for text analysis and text-to-speech
- A sample video file to process

## Usage
1. Extract text information from video frames using OpenAI's API.
2. Convert the extracted text into voice narration.
3. Combine the narration with the original video using `PHP-FFmpeg`.
4. Output a new video with voice narration.

## Contributing
Contributions and feedback are welcome! Please feel free to submit issues or pull requests to improve the project.
