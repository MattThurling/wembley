
<template>
  <div>
  <hr class="mt-0"/>
    <div class="row">
      <div class="col-sm-8 mb-3">
        <div class="card card-default">
          <div class="card-header px-2 py-0">
            Live <small class="text-muted" v-if="activeUser" >{{ activeUser.name }} is typing...</small>
          </div>
          <div class="card-body p-0">
             <ul id="chat-list" class="list-unstyled" v-chat-scroll>
                 <li class="pl-2" v-for="(message, index) in messages" :key="index" >
                     <span v-if="message.system_signature">🏆</span>
                     <strong v-else>{{ message.user.name}}</strong>
                     {{ message.message }}
                 </li>
             </ul>
          </div>

          <input
              @keydown="sendTypingEvent"
              @keyup.enter="sendMessage"
              v-model="newMessage"
              type="text"
              name="message"
              placeholder="Enter your message..."
              class="form-control"
              id="chat-message">
        </div>
      </div>

        <div class="col-sm-4">
            <h6 class="text-center">ACTIVE PLAYERS</h6>
            <ul class="list-group">
                <li class="list-group-item py-0" v-for="(u, index) in users" :key="index">
                    {{ u.name }}
                </li>
            </ul>
        </div>

    </div>
     <hr class="mt-0"/>
   </div>
</template>

<script>
    export default {
        // TODO At the moment, we are passing in the user AND the player even though
        // this means redundant data. May want to refactor after examining what's happening
        // with Echo and Pusher
        props:['tournament_id', 'user'],
        data() {
            return {
                messages: [{
                    message: '',
                    system_signature: null,
                    user: {name: ''}
                }],
                newMessage: '',
                users:[],
                activeUser: false,
                typingTimer: false,
            }
        },
        created() {
            this.fetchMessages();
            Echo.join(this.tournament_id)
                .here(user => {
                    console.log('HERE: ' + JSON.stringify(user));
                    this.users = user;
                })
                .joining(user => {
                    console.log('JOINING: ' + JSON.stringify(user));
                    this.users.push(user);
                })
                .leaving(user => {
                    console.log('LEAVING: ' + JSON.stringify(user));
                    this.users = this.users.filter(u => u.id != user.id);
                })
                .listen('ChatEvent',(event) => {
                    console.log('LISTEN: ' + JSON.stringify(event));
                    this.messages.push(event.chat);
                })
                .listenForWhisper('typing', user => {
                   this.activeUser = user;
                    if(this.typingTimer) {
                        clearTimeout(this.typingTimer);
                    }
                   this.typingTimer = setTimeout(() => {
                       this.activeUser = false;
                   }, 1000);
                })
        },
        methods: {
            fetchMessages() {
                axios.get('/api/tournament/' + this.tournament_id + '/messages').then(response => {
                    this.messages = response.data;
                })
            },
            sendMessage() {
                console.log('SEND :' + JSON.stringify(this.player));
                this.messages.push({
                    user: this.user,
                    message: this.newMessage
                });
                axios.post('/api/tournament/' + this.tournament_id + '/messages', {message: this.newMessage});
                this.newMessage = '';
            },
            sendTypingEvent() {
                Echo.join(this.tournament_id)
                    .whisper('typing', this.user);
                console.log(this.user.name + ' is typing now')
            }
        }
    }
</script>
