# rauchGPT Project Outline

## Project Description

rauchGPT is a demo chatbot web application designed for educational use in a school class. The chatbot simulates a conversation with a character who is a long-term smoker struggling to quit, providing students with an engaging and realistic scenario to practice communication, empathy, and digital literacy. The application is built using the TALL stack (TailwindCSS, Alpine.js, Laravel, and Livewire), ensuring a modern, responsive, and interactive user experience.

The Demo should be in german. (also add a Privacy Policy to the project, and explain that the chat history is stored locally in the browser's localStorage and not shared with anyone and no data is stored anywhere. the tool uses the API of OpenAI, but does not store any data there.)

**Key Features:**
- **Local-Only Data Storage:** All chat history is stored exclusively in the browser's localStorage, ensuring privacy and no server-side data retention.
- **OpenAI Integration:** The chatbot leverages the OpenAI API to generate responses, with every prompt tailored to reflect the bot's unique personality and struggles.
- **Educational Focus:** Students interact with the bot by asking questions, observing its responses, and reflecting on the challenges of addiction and behavioral change.
- **Modern Tech Stack:** Built with Laravel 12, TailwindCSS, Alpine.js, and Livewire for a seamless, real-time chat experience.

This project is structured to be approachable for students and educators, with clear steps, helpful comments, and a focus on both technical and social learning outcomes.

## 1. Project Setup
- [x] Ensure Laravel 12 is installed and working
- [x] Install and configure TailwindCSS
- [x] Install and configure Livewire
- [x] Install and configure Alpine.js
- [x] Set up environment variables (including OpenAI API key)

## 2. UI/UX Design
- [x] Design a simple, modern chat interface using TailwindCSS
- [x] Add a chat message list (user and bot messages)
- [x] Add a message input box and send button
- [x] Display bot personality ("longterm smoker who wants to stop but can't")
- [x] Responsive/mobile-friendly layout

## 3. Livewire Components
- [ ] Create a Livewire component for the chat interface
- [ ] Handle sending and displaying messages in real-time
- [ ] Integrate Alpine.js for UI interactivity (e.g., auto-scroll, input focus)

## 4. LocalStorage Integration
- [ ] Store chat history in browser localStorage (no server-side storage)
- [ ] On page load, load chat history from localStorage
- [ ] On new message, update localStorage
- [ ] Option to clear/reset chat history

## 5. OpenAI API Integration
- [ ] Create a backend endpoint to proxy requests to OpenAI (using API key from env)
- [ ] Send user messages to OpenAI and receive responses
- [ ] Inject bot personality into every prompt ("You are a longterm smoker who wants to stop smoking but can't...")
- [ ] Handle API errors gracefully

## 6. Demo & Testing
- [ ] Test chat flow end-to-end
- [ ] Ensure only localStorage is used for chat history
- [ ] Test on different devices/browsers
- [ ] Prepare demo instructions for students

## 7. Polish & Delivery
- [ ] Add helpful comments in code for students
- [ ] Polish UI (colors, spacing, etc.)
- [ ] Write a short README with setup and usage instructions

---

**Next Step:**
Start with Project Setup (Section 1).
