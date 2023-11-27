<?php

// Function to generate a story using OpenAI GPT-3.5
function generateStory($title) {
    // Replace 'YOUR_OPENAI_API_KEY' with your actual OpenAI API key
    $apiKey = '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';
    $prompt = "Create an interesting story with the title: '$title'. Once upon a time, ";
    $url = 'https://api.openai.com/v1/engines/davinci/completions';

    $data = [
        'prompt' => $prompt,
        'max_tokens' => 300, // Adjust as needed
    ];

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Curl error:' . curl_error($ch);
    }

    curl_close($ch);

    $result = json_decode($response, true);

    // Check for errors in the response
    if (isset($result['choices'][0]['text'])) {
        $story = $result['choices'][0]['text'];

        // Output the story as compact JSON
        $output = [
            'title' => $title,
            'story' => $story,
        ];

        return json_encode($output, JSON_UNESCAPED_SLASHES);
    } else {
        // Print the error response for debugging
        echo 'Error in OpenAI response: ' . json_encode($result);
        return json_encode(['error' => 'Error generating story'], JSON_UNESCAPED_SLASHES);
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? $_POST['title'] : 'Untitled';
    $storyJson = generateStory($title);

    // Output the generated story in compact JSON format
    echo "<h2>Story: $title</h2>";
    echo "<pre>$storyJson</pre>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Story Generator</title>
</head>
<body>

<h1>Story Generator</h1>

<!-- Form for user input -->
<form method="post" action="">
    <label for="title">Enter the title:</label>
    <input type="text" id="title" name="title" required>
    <button type="submit">Generate Story</button>
</form>

</body>
</html>
