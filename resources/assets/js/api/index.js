export default {
  methods: {
    apiGet(url) {

      axios.get('/api/' + url).then(response => {
          console.log(response.data);
      });

    },
    apiPost(url, data={}) {
      console.log(url);
      axios.post('/api/' + url, data).then(response => {
        console.log(response.data);
      });
    }
  }
}