<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot Customer Service</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .chat-container {
            height: 70vh;
            overflow-y: auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }
        
        .message {
            max-width: 80%;
            margin-bottom: 15px;
            padding: 12px 16px;
            border-radius: 18px;
            position: relative;
            word-wrap: break-word;
        }
        
        .user-message {
            background-color: #0d6efd;
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 5px;
        }
        
        .bot-message {
            background-color: #e9ecef;
            color: #212529;
            align-self: flex-start;
            border-bottom-left-radius: 5px;
        }
        
        .message-time {
            font-size: 0.75rem;
            opacity: 0.7;
            margin-top: 5px;
            text-align: right;
        }
        
        .typing-indicator {
            display: none;
            align-self: flex-start;
            margin-bottom: 15px;
        }
        
        .typing-dots {
            display: inline-flex;
            align-items: center;
            height: 20px;
        }
        
        .typing-dots .dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #6c757d;
            margin-right: 4px;
            animation: typingAnimation 1.4s infinite ease-in-out;
        }
        
        @keyframes typingAnimation {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-5px); }
        }
        
        #chat-input {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border-right: none;
        }
        
        #send-button {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
        
        .chat-header {
            background-color: #0d6efd;
            color: white;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0;
        }
        
        .intent-badge {
            font-size: 0.7rem;
            background: rgba(0,0,0,0.1);
            padding: 2px 8px;
            border-radius: 10px;
            margin-left: 8px;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="shadow">
                    <!-- Header -->
                    <div class="chat-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-robot me-2"></i>Customer Service Bot
                        </h5>
                        <small class="opacity-75">Online</small>
                    </div>
                    
                    <!-- Chat Area -->
                    <div id="chat-container" class="chat-container">
                        <!-- Initial Bot Message -->
                        <div class="bot-message message">
                            <p class="mb-0">Halo! Ada yang bisa saya bantu?</p>
                            <div class="message-time">Baru saja</div>
                        </div>
                    </div>
                    
                    <!-- Input Area -->
                    <div class="p-3 bg-white border-top">
                        <div class="input-group">
                            <input type="text" id="chat-input" class="form-control" 
                                   placeholder="Ketik pesan Anda..." autocomplete="off">
                            <button id="send-button" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Generate session ID
            const sessionId = 'session_' + Math.random().toString(36).substring(2, 15);
            
            // DOM Elements
            const chatContainer = $('#chat-container');
            const chatInput = $('#chat-input');
            const sendButton = $('#send-button');
            
            // Auto focus input
            chatInput.focus();
            
            // Function to add message to chat
            function addMessage(sender, text, intent = null) {
                const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                const messageElement = $('<div class="message"></div>');
                
                if (sender === 'user') {
                    messageElement.addClass('user-message');
                    messageElement.html(`
                        <p class="mb-0">${text}</p>
                        <div class="message-time">${time}</div>
                    `);
                } else {
                    messageElement.addClass('bot-message');
                    let intentBadge = '';
                    if (intent) {
                        intentBadge = `<span class="intent-badge">${intent}</span>`;
                    }
                    messageElement.html(`
                        <p class="mb-0">${text} ${intentBadge}</p>
                        <div class="message-time">${time}</div>
                    `);
                }
                
                chatContainer.append(messageElement);
                chatContainer.scrollTop(chatContainer[0].scrollHeight);
            }
            
            // Function to show typing indicator
            function showTyping() {
                const typingElement = $(`
                    <div class="typing-indicator">
                        <div class="bot-message message">
                            <div class="typing-dots">
                                <span class="dot"></span>
                                <span class="dot"></span>
                                <span class="dot"></span>
                            </div>
                        </div>
                    </div>
                `);
                
                chatContainer.append(typingElement);
                typingElement.fadeIn(200);
                chatContainer.scrollTop(chatContainer[0].scrollHeight);
            }
            
            // Function to hide typing indicator
            function hideTyping() {
                $('.typing-indicator').fadeOut(200, function() {
                    $(this).remove();
                });
            }
            
            // Handle send message
            function sendMessage() {
                const message = chatInput.val().trim();
                if (message === '') return;
                
                // Add user message
                addMessage('user', message);
                chatInput.val('');
                
                // Show typing indicator
                showTyping();
                
                // Send to server
                $.ajax({
                    url: '/chat/send',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // For Laravel CSRF protection
                        message: message,
                        session_id: sessionId
                    },
                    success: function(response) {
                        hideTyping();
                        if (response.success) {
                            addMessage('bot', response.response, response.intent);
                        } else {
                            addMessage('bot', 'Maaf, terjadi kesalahan. Silakan coba lagi.');
                        }
                    },
                    error: function(xhr) {
                        hideTyping();
                        let errorMessage = 'Terjadi kesalahan koneksi';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        addMessage('bot', errorMessage);
                    }
                });
            }
            
            // Event listeners
            sendButton.on('click', sendMessage);
            chatInput.on('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    sendMessage();
                }
            });
        });
    </script>
</body>
</html>