import { GoogleGenerativeAI } from "@google/generative-ai";

const genAI = new GoogleGenerativeAI("AIzaSyC8GPmSUt0opFJzCFIGcAFovlPDzB-Pvuw");

// Get the generative model (in this case, "gemini-pro")w
const model = genAI.getGenerativeModel({ model: "gemini-pro" });

const chatInput = document.querySelector(".chat-input textarea");
const sendButton = document.querySelector("#send-btn");
const chatContainer = document.querySelector(".chatbox");
const chatbotToggler = document.querySelector(".chatbot-toggler");
const closeBtn = document.querySelector(".close-btn");

// Function to get response from the chat model
const getChatResponse = async (userText) => {
  try {
    const result = await model.generateContent(userText);
    const response = await result.response.text();
    return response.trim();
  } catch (error) {
    return "Oops! Something went wrong while retrieving the response. Please try again.";
  }
};

// Function to create chat <li> element
const createChatLi = (message, className) => {
  const chatLi = document.createElement("li");
  chatLi.classList.add("chat", className);
  chatLi.innerHTML = ` 
    <p>${message}</p>`;
  return chatLi;
};

// Function to handle sending user message and getting response
const handleChat = async () => {
  const userText = chatInput.value.trim();
  if (!userText) return;
  chatInput.value = ""; // Clear the input field

  // Append user's message to chat container
  const userMessageLi = createChatLi(userText, "outgoing");
  chatContainer.appendChild(userMessageLi);

  // Display "Thinking..." message while waiting for the response
  const thinkingLi = createChatLi("Thinking...", "incoming");
  chatContainer.appendChild(thinkingLi);
  chatContainer.scrollTo(0, chatContainer.scrollHeight);

  // Get response from the model after a delay
  setTimeout(async () => {
    const response = await getChatResponse(userText);
    // Replace "Thinking..." message with the actual AI response
    thinkingLi.querySelector("p").textContent = response;
    chatContainer.scrollTo(0, chatContainer.scrollHeight);
  }, 600);
};

// Event listener for input event to adjust textarea height
chatInput.addEventListener("input", () => {
  chatInput.style.height = `${inputInitHeight}px`;
  chatInput.style.height = `${chatInput.scrollHeight}px`;
});

// Event listener for Enter key press (without shift) for desktop
chatInput.addEventListener("keydown", (e) => {
  if (e.key === "Enter" && !e.shiftKey && window.innerWidth > 800) {
    e.preventDefault();
    handleChat();
  }
});

// Event listener for send button click
sendButton.addEventListener("click", handleChat);

// Event listener for close button click
closeBtn.addEventListener("click", () =>
  document.body.classList.remove("show-chatbot")
);

// Event listener for chatbot toggler click
chatbotToggler.addEventListener("click", () =>
  document.body.classList.toggle("show-chatbot")
);
