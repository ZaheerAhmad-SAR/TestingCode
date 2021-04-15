<!--<template>-->
<!--    <div>-->
<!--        <div class="card card-body mb-2" v-for="modilitie in modilities" v-bind:key="modilitie.id">-->
<!--            <h3>{{modilitie.modility_name}}</h3>-->
<!--        </div>-->
<!--    </div>-->
<!--</template>-->

<template>
    <div>
        <span class="badge badge-pill badge-danger updateCounter"style="height: 20px;top: 12px;">{{message}}</span>
    </div>
</template>


<script>
// export default {
//     data()
//     {
//         return {
//             modilities: [],
//             modilitie: {
//                 id: '',
//                 modility_name: ''
//             }
//         }
//     },
//     created()
//     {
//     this.fetchModilities();
//     },
//     methods: {
//         fetchModilities()
//         {
//             fetch('/ocap_new/sites').then(res=>res.json())
//             .then(res =>{
//                 this.modilities = res;
//             })
//         }
//      },
//     mounted() {
//         window.Echo.channel('channel').listen('UserShouldNotifyQuery',(e)=>{
//             console.log(e)
//         })
//     }
// }


export default {
    data: () =>({
    message : '0'
    }),

    mounted() {
        window.Echo.channel('channel').listen('UserShouldNotifyQuery',(e)=>{
            console.log(e.data);
            console.log($);
            let record = e.data ;
           this.message = record;
        })
    }
}
</script>
