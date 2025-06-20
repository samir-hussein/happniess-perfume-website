<div class="luxury-chat">
    <div class="chat-toggle">
        <i class="fas fa-comment-dots"></i>
        @if ($chat)
            <span
                class="pulse-dot {{ $chat->messages->where('sender', 'admin')->where('read', false)->count() > 0 ? '' : 'pulse-hide' }}"></span>
        @else
            <span class="pulse-dot pulse-hide"></span>
        @endif
    </div>

    <div class="chat-container">
        <div class="chat-header">
            <div class="perfume-icon">
                <i class="fas fa-spray-can"></i>
            </div>
            <h4>{{ __('Happiness Perfume') }}</h4>
            <div class="chat-actions">
                {{-- <i class="fas fa-expand"></i> --}}
                <i class="fas fa-times close-chat"></i>
            </div>
        </div>

        <div class="chat-body">
            <div class="welcome-message">
                <div class="perfume-bottle">
                    <i class="fas fa-wine-bottle"></i>
                </div>
                <p>{{ __('Welcome to Happiness Perfume') }}<br><span>{{ __('Our fragrance experts are here to assist you') }}</span>
                </p>
            </div>

            <div class="message-container">
                @if ($chat)
                    @foreach ($chat->messages as $message)
                        <div class="message {{ $message->sender == 'client' ? 'user' : 'bot' }}">
                            <div class="avatar">{{ $message->sender == 'client' ? 'You' : 'HP' }}</div>
                            <div class="bubble">
                                <p>{{ $message->content }}</p>
                                <span class="time">{{ $message->created_at->format('Y-m-d h:i a') }}</span>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="chat-footer">
            <div class="input-container">
                <textarea placeholder="{{ __('Type your question...') }}"></textarea>
                <div class="input-actions">
                    {{-- <i class="fas fa-paperclip"></i> --}}
                    <button class="send-btn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
            <p class="disclaimer">{{ __('Typically replies in under 15 minutes') }}</p>
        </div>
    </div>
</div>
