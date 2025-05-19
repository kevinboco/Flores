.animated-button {
  background: linear-gradient(135deg, #6b8ce3, #4a90e2);
  color: white;
  border: none;
  padding: 14px 28px;
  font-size: 18px;
  font-weight: 600;
  border-radius: 12px;
  cursor: pointer;
  margin: 8px 0;
  box-shadow: 0 6px 12px rgba(74, 144, 226, 0.4);
  transition: 
    transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), 
    box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1),
    background-position 0.5s ease;
  background-size: 200% 200%;
  background-position: left center;
  opacity: 0;
  transform: translateY(100px);
}

.animated-button:hover {
  transform: scale(1.08);
  box-shadow: 0 10px 20px rgba(74, 144, 226, 0.6);
  background-position: right center;
}

.animated-button:active {
  transform: scale(0.95);
  box-shadow: 0 4px 10px rgba(74, 144, 226, 0.8);
}

.animated-button.animate-up {
  opacity: 1;
  transform: translateY(0);
}
