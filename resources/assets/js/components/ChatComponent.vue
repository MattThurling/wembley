
<template>
   <div class="row">

       <div class="col-sm-8 mb-3">
           <div class="card card-default">
              <div class="card-header">
                Chat <small class="text-muted" v-if="activeUser" >{{ activeUser.name }} is typing...</small>
              </div>
               <div class="card-body p-0">
                   <ul class="list-unstyled" style="height:180px; overflow-y:scroll" v-chat-scroll>
                       <li class="p-2" v-for="(message, index) in messages" :key="index" >
                           <strong>{{ message.player.user.name }}</strong>
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
                    class="form-control">
           </div>
       </div>

        <div class="col-sm-4">
            <div class="card card-default">
                <div class="card-header">Active Players</div>
                <div class="card-body">
                    <ul>
                        <li class="py-2" v-for="(user, index) in users" :key="index">
                            {{ user.name }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

   </div>
</template>

<script>
    export default {
        // TODO At the moment, we are passing in the user AND the player even though
        // this means redundant data. May want to refactor after examining what's happening
        // with Echo and Pusher
        props:['player', 'user'],
        data() {
            return {
                messages: [],
                newMessage: '',
                users:[],
                activeUser: false,
                typingTimer: false,
            }
        },
        created() {
            this.fetchMessages();
            Echo.join('chat')
                .here(user => {
                    this.users = user;
                })
                .joining(user => {
                    this.users.push(user);
                })
                .leaving(user => {
                    this.users = this.users.filter(u => u.id != user.id);
                })
                .listen('ChatEvent',(event) => {
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
                axios.get(window.location.href + '/messages').then(response => {
                    this.messages = response.data;
                })
            },
            sendMessage() {
                this.messages.push({
                    player: this.player,
                    message: this.newMessage
                });
                axios.post(window.location.href + '/messages', {message: this.newMessage});
                this.newMessage = '';
            },
            sendTypingEvent() {
                Echo.join('chat')
                    .whisper('typing', this.user);
                console.log(this.user.name + ' is typing now')
            }
        }
    }
</script>
