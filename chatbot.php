<?php
// Initialize variables
$structured = null;
$responseText = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $message = $_POST['message'] ?? '';
    $specialization = $_POST['specialization'] ?? 'general';

    try {
        // Make API call to get response
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://ai-doctor-api-ai-medical-chatbot-healthcare-ai-assistant.p.rapidapi.com/chat?noqueue=1",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'message' => $message,
                'specialization' => $specialization,
                'language' => 'en'
            ]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "X-RapidAPI-Host: ai-doctor-api-ai-medical-chatbot-healthcare-ai-assistant.p.rapidapi.com",
                "X-RapidAPI-Key: 523ef39ba2msh2626129b4772612p12f0d0jsn34fc3ac242e5"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $responseText = "API Error: " . $err;
        } else {
            // Parse API response
            $result = json_decode($response, true);
            
            // Structure the response
            $structured = [
                'message' => $result['response'] ?? '',
                'recommendations' => $result['recommendations'] ?? [],
                'warnings' => $result['warnings'] ?? [],
                'references' => $result['references'] ?? [],
                'followUp' => $result['followUpQuestions'] ?? []
            ];
        }
    } catch (Exception $e) {
        $responseText = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthGuide AI Assistant</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='80' font-size='80'>🩺</text></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --accent-color: #e74c3c;
            --light-gray: #f8f9fa;
            --dark-blue: #2c3e50;
        }
        
        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            padding-bottom: 40px;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .chat-container {
            max-width: 900px;
            margin: 30px auto;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.1);
            background: white;
        }
        
        .chat-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .chat-body {
            padding: 30px;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #ddd;
        }
        
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
            border-color: var(--primary-color);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
        }
        
        .btn-secondary {
            background-color: #6c757d;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .response-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-top: 30px;
            border: none;
        }
        
        .card-header {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 15px 20px;
            font-weight: 600;
            border-bottom: 1px solid #eaeaea;
        }
        
        .section-card {
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            border: 1px solid #eaeaea;
        }
        
        .section-header {
            padding: 15px 20px;
            font-weight: 600;
            color: var(--dark-blue);
            display: flex;
            align-items: center;
            border-bottom: 1px solid #eaeaea;
        }
        
        .section-header i {
            margin-right: 10px;
            width: 24px;
            text-align: center;
        }
        
        .section-body {
            padding: 20px;
        }
        
        .section-insight {
            background-color: rgba(52, 152, 219, 0.1);
            border-left: 4px solid var(--primary-color);
        }
        
        .section-recommendations {
            background-color: rgba(46, 204, 113, 0.1);
            border-left: 4px solid var(--secondary-color);
        }
        
        .section-warnings {
            background-color: rgba(231, 76, 60, 0.1);
            border-left: 4px solid var(--accent-color);
        }
        
        .section-references {
            background-color: rgba(44, 62, 80, 0.1);
            border-left: 4px solid var(--dark-blue);
        }
        
        .section-followup {
            background-color: rgba(241, 196, 15, 0.1);
            border-left: 4px solid #f1c40f;
        }
        
        .list-group-item {
            border: none;
            padding: 10px 5px;
            position: relative;
            padding-left: 30px;
            background: transparent;
        }
        
        .list-group-item:before {
            content: "";
            position: absolute;
            left: 10px;
            top: 19px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--primary-color);
        }
        
        .section-recommendations .list-group-item:before {
            background: var(--secondary-color);
        }
        
        .section-warnings .list-group-item:before {
            background: var(--accent-color);
        }
        
        .typing-animation {
            display: inline-block;
            height: 20px;
            position: relative;
        }
        
        .typing-animation span {
            background-color: var(--primary-color);
            border-radius: 50%;
            display: inline-block;
            height: 6px;
            width: 6px;
            margin: 0 2px;
            animation: typing 1.5s infinite ease-in-out;
        }
        
        .typing-animation span:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        .typing-animation span:nth-child(3) {
            animation-delay: 0.4s;
        }
        
        @keyframes typing {
            0% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0); }
        }
        
        .specialization-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .specialization-option {
            flex: 1;
            min-width: 120px;
            text-align: center;
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 15px 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .specialization-option i {
            font-size: 24px;
            margin-bottom: 10px;
            color: #777;
            transition: all 0.3s ease;
        }
        
        .specialization-option.active {
            border-color: var(--primary-color);
            background-color: rgba(52, 152, 219, 0.1);
        }
        
        .specialization-option.active i {
            color: var(--primary-color);
        }
        
        .chat-input-group {
            position: relative;
        }
        
        .chat-input-group .form-control {
            padding-right: 120px;
        }
        
        .chat-input-group .btn {
            position: absolute;
            right: 5px;
            top: 5px;
            bottom: 5px;
            z-index: 5;
        }
        
        .followup-question {
            background-color: rgba(52, 152, 219, 0.1);
            border-radius: 20px;
            padding: 8px 15px;
            margin: 5px;
            display: inline-block;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid rgba(52, 152, 219, 0.3);
        }
        
        .followup-question:hover {
            background-color: rgba(52, 152, 219, 0.2);
            transform: translateY(-2px);
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        
        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }
        
        footer {
            text-align: center;
            padding: 20px;
            color: #777;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .chat-body {
                padding: 20px 15px;
            }
            
            .specialization-option {
                min-width: 100px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-heartbeat text-danger me-2"></i>HealthGuide AI 
            </a><small>powerd by pharmacyWala</small>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php"><i class="fas fa-home me-1"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-info-circle me-1"></i> About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-question-circle me-1"></i> FAQ</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="chat-container">
            <div class="chat-header">
                <h3><i class="fas fa-robot me-2"></i>AI Medical Assistant</h3>
                <p class="mb-0">Get professional medical guidance powered by AI</p>
            </div>
            
            <div class="chat-body">
                <form method="POST" id="questionForm">
                    <input type="hidden" id="specialization" name="specialization" value="general">
                    
                    <div class="mb-4">
                        <label for="message" class="form-label"><i class="fas fa-comment-medical me-2"></i>What's your medical question?</label>
                        <div class="chat-input-group">
                            <input type="text" class="form-control" id="message" name="message" placeholder="E.g., What are the symptoms of high blood pressure?" required>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i> Ask</button>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-stethoscope me-2"></i>Select a medical specialization</label>
                        <div class="specialization-selector">
                            <div class="specialization-option active" data-value="general">
                                <i class="fas fa-user-md"></i>
                                <span>General</span>
                            </div>
                            <div class="specialization-option" data-value="cardiology">
                                <i class="fas fa-heart"></i>
                                <span>Cardiology</span>
                            </div>
                            <div class="specialization-option" data-value="neurology">
                                <i class="fas fa-brain"></i>
                                <span>Neurology</span>
                            </div>
                            <div class="specialization-option" data-value="dermatology">
                                <i class="fas fa-allergies"></i>
                                <span>Dermatology</span>
                            </div>
                            <div class="specialization-option" data-value="pediatrics">
                                <i class="fas fa-baby"></i>
                                <span>Pediatrics</span>
                            </div>
                        </div>
                    </div>

                    <!-- Add this back button -->
                    <div class="mb-3">
                        <button class="btn btn-secondary" onclick="history.back()">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </button>
                    </div>
                </form>
                
                <div class="loading" id="loadingIndicator">
                    <div class="typing-animation">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <p class="mt-2 text-muted">Our AI doctor is analyzing your question...</p>
                </div>
                
                <?php if ($structured): ?>
                <div class="response-card card fade-in" id="responseContainer">
                    <div class="card-header d-flex align-items-center">
                        <i class="fas fa-robot me-2 text-primary"></i>
                        <span>AI Doctor Response</span>
                    </div>
                    <div class="card-body">
                        <!-- Medical Insight Section -->
                        <div class="section-card section-insight">
                            <div class="section-header">
                                <i class="fas fa-brain text-primary"></i>
                                <span>Medical Insight</span>
                            </div>
                            <div class="section-body">
                                <p><?= htmlspecialchars($structured['message']) ?></p>
                            </div>
                        </div>
                        
                        <!-- Recommendations Section -->
                        <div class="section-card section-recommendations">
                            <div class="section-header">
                                <i class="fas fa-check-circle text-success"></i>
                                <span>Recommendations</span>
                            </div>
                            <div class="section-body">
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($structured['recommendations'] as $rec): ?>
                                        <li class="list-group-item"><?= htmlspecialchars($rec) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Warnings Section -->
                        <div class="section-card section-warnings">
                            <div class="section-header">
                                <i class="fas fa-exclamation-triangle text-danger"></i>
                                <span>Health Warnings</span>
                            </div>
                            <div class="section-body">
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($structured['warnings'] as $warn): ?>
                                        <li class="list-group-item"><?= htmlspecialchars($warn) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- References Section -->
                        <div class="section-card section-references">
                            <div class="section-header">
                                <i class="fas fa-book-medical text-secondary"></i>
                                <span>Medical References</span>
                            </div>
                            <div class="section-body">
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($structured['references'] as $ref): ?>
                                        <li class="list-group-item"><?= htmlspecialchars($ref) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Follow-up Questions Section -->
                        <div class="section-card section-followup">
                            <div class="section-header">
                                <i class="fas fa-comment-dots text-warning"></i>
                                <span>Suggested Follow-up Questions</span>
                            </div>
                            <div class="section-body">
                                <div class="d-flex flex-wrap">
                                    <?php foreach ($structured['followUp'] as $follow): ?>
                                        <div class="followup-question" onclick="askFollowup('<?= htmlspecialchars($follow) ?>')">
                                            <?= htmlspecialchars($follow) ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Disclaimer -->
                        <div class="alert alert-warning mt-4" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Medical Disclaimer:</strong> This information is for educational purposes only and not intended to replace professional medical advice. Always consult with a qualified healthcare provider for medical concerns.
                        </div>
                    </div>
                </div>
                <?php elseif (!empty($responseText)): ?>
                <div class="alert alert-danger fade-in" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Error:</strong> <?= htmlspecialchars($responseText) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Footer -->
        <footer>
            <p>HealthGuide AI Assistant &copy; 2025 | Not a substitute for professional medical advice</p>
        </footer>
    </div>

    <script>
        // Specialization selection
        document.querySelectorAll('.specialization-option').forEach(option => {
            option.addEventListener('click', function() {
                // Clear previous selection
                document.querySelectorAll('.specialization-option').forEach(el => {
                    el.classList.remove('active');
                });
                
                // Set new selection
                this.classList.add('active');
                document.getElementById('specialization').value = this.dataset.value;
            });
        });
        
        // Handle form submission with loading animation
        document.getElementById('questionForm').addEventListener('submit', function(e) {
            const responseContainer = document.getElementById('responseContainer');
            const loadingIndicator = document.getElementById('loadingIndicator');
            
            if (responseContainer) {
                responseContainer.style.display = 'none';
            }
            
            loadingIndicator.style.display = 'block';
            
            // Actual form submission will happen normally
        });
        
        // Function to handle follow-up question clicks
        function askFollowup(question) {
            document.getElementById('message').value = question;
            document.getElementById('questionForm').submit();
        }
        
        // Add smooth scrolling to response when it appears
        window.addEventListener('load', function() {
            const responseContainer = document.getElementById('responseContainer');
            if (responseContainer) {
                responseContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    </script>
</body>
</html>