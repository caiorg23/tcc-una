<div class="chat-widget" id="chatWidget">
  <div class="chat-dot"></div>
  <button class="chat-btn" onclick="toggleChat()">💬</button>
</div>

<div class="chat-panel" id="chatPanel">
  <div class="chat-header">
    <div class="chat-avatar">🚗</div>
    <div class="chat-header-info">
      <h3>Assistente CJOTA</h3>
      <p><span class="online-dot"></span> Online agora</p>
    </div>
    <button class="chat-close" onclick="toggleChat()">✕</button>
  </div>

  <div class="chat-messages" id="chatMessages">
    <div class="msg-bot">
      Olá! Sou o assistente do Padrão CJOTA 🚗<br>Como posso ajudar?
    </div>
  </div>

  <div class="quick-btns" id="quickBtns">
    <button class="quick-btn" onclick="quickReply('Endereço')">📍 Endereço</button>
    <button class="quick-btn" onclick="quickReply('Horários')">🕐 Horários</button>
    <button class="quick-btn" onclick="quickReply('Serviços')">🔧 Serviços</button>
    <button class="quick-btn" onclick="quickReply('Preços')">💰 Preços</button>
    <button class="quick-btn" onclick="quickReply('WhatsApp')">📱 WhatsApp</button>
  </div>

  <div class="chat-input-row">
    <input class="chat-input" id="chatInput" placeholder="Sua mensagem..." onkeydown="if(event.key==='Enter') sendMsg()">
    <button class="chat-send" onclick="sendMsg()">➤</button>
  </div>
</div>
