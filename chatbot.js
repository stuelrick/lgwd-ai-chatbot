/**
 * LGWD AI Chatbot JavaScript
 * Professional AI Chatbot with n8n Chat Trigger Integration
 * 
 * Author: Stuart Elrick - Lions Gate Web Design
 * Website: https://lionsgatewebdesign.com
 * Repository: https://github.com/stuelrick/lgwd-ai-chatbot
 * License: GPL v2 or later
 */

(function() {
    "use strict";

    // Wait for DOM to be ready
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initChatbot);
    } else {
        initChatbot();
    }

    function initChatbot() {
        // Check if config is available
        if (typeof LGWDChatbotConfig === "undefined") {
            console.error("LGWD Chatbot: Configuration not found");
            return;
        }

        const config = LGWDChatbotConfig;
        
        // Validate chat URL (now using chatUrl instead of webhookUrl)
        const chatUrl = config.chatUrl || config.webhookUrl; // Support both for backward compatibility
        if (!chatUrl) {
            console.error("LGWD Chatbot: Chat URL not configured");
            return;
        }

        // Get customizable text content
        const siteName = config.siteName || "Website";
        const aiName = config.aiName || "AI Assistant";
        const greetingMessage = config.greetingMessage || `Hello! How can I help you with ${siteName} today?`;
        const placeholderText = config.placeholderText || "Ask a question...";
        const fallbackMessage = config.fallbackMessage || "Sorry, I could not understand that. Please try rephrasing your question.";

        // Detect mobile devices
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || window.innerWidth <= 768;
        
        // Responsive dimensions
        const chatWindowWidth = isMobile ? "297px" : "350px"; // 15% smaller on mobile (350 * 0.85 = 297.5)
        const chatWindowHeight = isMobile ? "382px" : "450px"; // 15% smaller on mobile (450 * 0.85 = 382.5)
        const baseFontSize = isMobile ? "13px" : "14px"; // Base font size for all text
        const inputPadding = isMobile ? '8px 12px' : '10px 15px'; // Input padding
        const buttonSize = isMobile ? '36px' : '40px'; // Square button size for perfect circle

        // Generate a unique session ID for this user session
        const sessionId = "session-" + Math.random().toString(36).substr(2, 9) + "-" + Date.now().toString(36);

        // Create chatbot HTML structure with responsive sizing
        const chatbotHTML = `
            <div id="lgwd-chatbot-container" style="
                position: fixed;
                ${getPositionStyles(config.position)}
                z-index: 9999;
                font-family: Arial, sans-serif;
                font-size: ${baseFontSize};
            ">
                <div id="lgwd-chatbot-button" style="
                    width: 60px;
                    height: 60px;
                    border-radius: 50%;
                    background-color: ${config.primaryColor};
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                    transition: all 0.3s ease;
                " onmouseover="this.style.transform=\\"scale(1.1)\\"" onmouseout="this.style.transform=\\"scale(1)\\"">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="white">
                        <path d="M20 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h4l4 4 4-4h4c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                    </svg>
                </div>

                <div id="lgwd-chatbot-window" style="
                    position: absolute;
                    ${getWindowPosition(config.position)}
                    width: ${chatWindowWidth};
                    height: ${chatWindowHeight};
                    background: white;
                    border-radius: 10px;
                    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
                    display: none;
                    flex-direction: column;
                    overflow: hidden;
                    font-size: ${baseFontSize};
                ">
                    <div id="lgwd-chatbot-header" style="
                        background: ${config.primaryColor};
                        color: white;
                        padding: 15px;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        font-size: ${baseFontSize};
                    ">
                        <span style="font-weight: bold; font-size: ${baseFontSize};">${config.title}</span>
                        <span id="lgwd-chatbot-close" style="
                            cursor: pointer;
                            font-size: 18px;
                            line-height: 1;
                        ">&times;</span>
                    </div>
                    
                    <div id="lgwd-chatbot-messages" style="
                        flex: 1;
                        overflow-y: auto;
                        padding: ${isMobile ? '15px' : '20px'};
                        background: #f8f9fa;
                        font-size: ${baseFontSize};
                    ">
                        <div class="lgwd-bot-message" style="
                            background: white;
                            padding: ${isMobile ? '10px' : '12px'};
                            border-radius: 15px 15px 15px 5px;
                            margin-bottom: 10px;
                            max-width: 85%;
                            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                            font-size: ${baseFontSize};
                            line-height: 1.4;
                        ">
                            ${greetingMessage}
                        </div>
                    </div>
                    
                    <div id="lgwd-chatbot-input-area" style="
                        padding: ${isMobile ? '12px' : '15px'};
                        background: white;
                        border-top: 1px solid #eee;
                        display: flex;
                        align-items: center;
                        gap: 10px;
                    ">
                        <input type="text" id="lgwd-chatbot-input" placeholder="${placeholderText}" style="
                            flex: 1;
                            padding: ${inputPadding};
                            border: 1px solid #ddd;
                            border-radius: 20px;
                            outline: none;
                            font-size: ${baseFontSize};
                            font-family: Arial, sans-serif;
                        ">
                        <button id="lgwd-chatbot-send" style="
                            background: ${config.primaryColor};
                            color: white;
                            border: none;
                            width: ${buttonSize};
                            height: ${buttonSize};
                            border-radius: 50%;
                            cursor: pointer;
                            outline: none;
                            font-size: ${baseFontSize};
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            flex-shrink: 0;
                        ">▶</button>
                    </div>
                </div>
            </div>

            <style>
                /* Mobile-specific styles */
                @media (max-width: 768px) {
                    #lgwd-chatbot-window {
                        max-width: 90vw;
                        max-height: 80vh;
                    }
                    
                    #lgwd-chatbot-messages {
                        font-size: ${baseFontSize} !important;
                    }
                    
                    .lgwd-user-message, .lgwd-bot-message {
                        font-size: ${baseFontSize} !important;
                        line-height: 1.4 !important;
                    }
                    
                    #lgwd-chatbot-input {
                        font-size: ${baseFontSize} !important;
                    }
                    
                    #lgwd-chatbot-send {
                        font-size: ${baseFontSize} !important;
                    }
                }

                /* Attribution watermark (subtle) */
                #lgwd-chatbot-messages::after {
                    content: "Powered by LGWD AI Chatbot";
                    font-size: 10px;
                    color: #ccc;
                    text-align: center;
                    display: block;
                    margin-top: 10px;
                    opacity: 0.7;
                }
            </style>
        `;

        // Add chatbot to page
        document.body.insertAdjacentHTML("beforeend", chatbotHTML);

        // Get elements
        const chatButton = document.getElementById("lgwd-chatbot-button");
        const chatWindow = document.getElementById("lgwd-chatbot-window");
        const closeButton = document.getElementById("lgwd-chatbot-close");
        const sendButton = document.getElementById("lgwd-chatbot-send");
        const messageInput = document.getElementById("lgwd-chatbot-input");
        const messagesContainer = document.getElementById("lgwd-chatbot-messages");

        // Event listeners
        chatButton.addEventListener("click", toggleChat);
        closeButton.addEventListener("click", closeChat);
        sendButton.addEventListener("click", sendMessage);
        messageInput.addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                sendMessage();
            }
        });

        function toggleChat() {
            const isVisible = chatWindow.style.display === "flex";
            chatWindow.style.display = isVisible ? "none" : "flex";
            if (!isVisible) {
                messageInput.focus();
            }
        }

        function closeChat() {
            chatWindow.style.display = "none";
        }

        function sendMessage() {
            const message = messageInput.value.trim();
            if (!message) return;

            // Add user message to chat
            addMessage(message, "user");
            messageInput.value = "";

            // Show typing indicator
            showTypingIndicator();

            // Log the session ID for debugging
            console.log("LGWD Chatbot: Using session ID:", sessionId);

            // Create an AbortController for timeout
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout

            // Send message to n8n Chat Trigger with the correct format
            const payload = {
                action: "sendMessage",
                sessionId: sessionId,
                chatInput: message
            };

            console.log("LGWD Chatbot: Sending payload to Chat Trigger:", payload);

            fetch(chatUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify(payload),
                signal: controller.signal
            })
            .then(response => {
                clearTimeout(timeoutId);
                console.log("LGWD Chatbot: Response received", response.status, response.statusText);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                hideTypingIndicator();
                console.log("LGWD Chatbot: Response data", data);
                
                let botMessage = fallbackMessage;
                
                // Handle different response formats from n8n Chat Trigger
                if (data.output) {
                    botMessage = data.output;
                } else if (data.response) {
                    botMessage = data.response;
                } else if (data.message) {
                    botMessage = data.message;
                } else if (data.text) {
                    botMessage = data.text;
                } else if (data.reply) {
                    botMessage = data.reply;
                } else if (data.answer) {
                    botMessage = data.answer;
                } else if (typeof data === "string") {
                    botMessage = data;
                }
                
                addMessage(botMessage, "bot");
            })
            .catch(error => {
                clearTimeout(timeoutId);
                hideTypingIndicator();
                
                let errorMessage = "Sorry, I am having trouble connecting right now. Please try again later.";
                
                if (error.name === "AbortError") {
                    errorMessage = "The AI is taking longer than expected to respond. Please try again with a simpler question.";
                    console.error("LGWD Chatbot: Request timeout after 30 seconds");
                } else if (error.message.includes("404")) {
                    errorMessage = "AI service is currently unavailable. Please contact us directly for assistance.";
                    console.error("LGWD Chatbot: Chat endpoint not found (404)");
                } else if (error.message.includes("500")) {
                    errorMessage = "AI service error. Please try again in a moment.";
                    console.error("LGWD Chatbot: Server error (500)");
                } else if (error.message.includes("CORS")) {
                    errorMessage = "Connection blocked by security settings. Please contact support.";
                    console.error("LGWD Chatbot: CORS error - check n8n CORS settings");
                } else {
                    console.error("LGWD Chatbot Error:", error);
                }
                
                addMessage(errorMessage, "bot");
            });
        }

        function addMessage(text, sender) {
            const isUser = sender === "user";
            const messageDiv = document.createElement("div");
            messageDiv.className = isUser ? "lgwd-user-message" : "lgwd-bot-message";
            messageDiv.style.cssText = `
                background: ${isUser ? config.primaryColor : "white"};
                color: ${isUser ? "white" : "black"};
                padding: ${isMobile ? '10px' : '12px'};
                border-radius: ${isUser ? "15px 15px 5px 15px" : "15px 15px 15px 5px"};
                margin-bottom: 10px;
                max-width: 85%;
                ${isUser ? "margin-left: auto;" : ""}
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                word-wrap: break-word;
                font-size: ${baseFontSize};
                line-height: 1.4;
            `;
            messageDiv.textContent = text;
            messagesContainer.appendChild(messageDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function showTypingIndicator() {
            const typingDiv = document.createElement("div");
            typingDiv.id = "lgwd-typing-indicator";
            typingDiv.style.cssText = `
                background: white;
                padding: ${isMobile ? '10px' : '12px'};
                border-radius: 15px 15px 15px 5px;
                margin-bottom: 10px;
                max-width: 85%;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                font-style: italic;
                color: #666;
                font-size: ${baseFontSize};
                line-height: 1.4;
            `;
            typingDiv.innerHTML = `
                <span>${aiName} is thinking</span>
                <span style="animation: blink 1.5s infinite;">...</span>
                <style>
                @keyframes blink {
                    0%, 50% { opacity: 1; }
                    51%, 100% { opacity: 0; }
                }
                </style>
            `;
            messagesContainer.appendChild(typingDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function hideTypingIndicator() {
            const typingIndicator = document.getElementById("lgwd-typing-indicator");
            if (typingIndicator) {
                typingIndicator.remove();
            }
        }
    }

    function getPositionStyles(position) {
        switch (position) {
            case "bottom-left":
                return "bottom: 20px; left: 20px;";
            case "top-right":
                return "top: 20px; right: 20px;";
            case "top-left":
                return "top: 20px; left: 20px;";
            case "bottom-right":
            default:
                return "bottom: 20px; right: 20px;";
        }
    }

    function getWindowPosition(position) {
        switch (position) {
            case "bottom-left":
                return "bottom: 80px; left: 0;";
            case "top-right":
                return "top: 80px; right: 0;";
            case "top-left":
                return "top: 80px; left: 0;";
            case "bottom-right":
            default:
                return "bottom: 80px; right: 0;";
        }
    }

})();
