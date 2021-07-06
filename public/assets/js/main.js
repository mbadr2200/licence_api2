const app = {
    data() {
      return {
        licences : [],
        message : "",
        isvalid : false
      }
    },
    methods :
    {
        fetchData () {
            console.log("before call");
            axios.get("../api/licence/getLicences.php",{
                headers:{
                    'Content-Type': 'application/json'
                }
            })
            .then((res) => {
                this.licences = res.data.data ; 
                this.message = res.data.message;
                if(res.data.data.status == "valid")
                {
                    this.isvalid = true
                }
                else
                {
                    this.isvalid = false
                }
            })
        }
    },
    mounted () {
        this.fetchData();
        console.log("mounted")
    }
}

Vue.createApp(app).mount('#app');

const application = Vue.createApp({});

application.component("login",{
    data() {
        return {
          
        }
      },
      template: `
        <button @click="count++">
          You clicked me {{ count }} times.
        </button>
        `
})