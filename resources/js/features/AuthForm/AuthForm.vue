<script setup>
import { reactive, ref } from 'vue';
import {http} from '@/shared/api/axios.js'

    const form = reactive({
        email: '',
        password: ''
    })

    const user = ref(null);

    


    const handleLogin = async () => {
       try{
             await http.get('/sanctum/csrf-cookie');
             await http.post('/api/login', {
                email: form.email,
                password: form.password
             });

            window.location.href = '/';

       }
       catch(e){
           console.log(e);
       }
    }
</script>

<template>
    <div class="mx-auto w-50 ">
        <form @submit.prevent="handleLogin" class="p-4 bg-light">
            <div class="form-group mb-4">
            <label>E-mail</label>
            <input  class="form-control" v-model="form.email" type="text"/>
           </div> 
           <div>
            <label>Password</label>
            <input  class="form-control  mb-4" v-model="form.password" type="password"/>
           </div>
           <button type="submit" class="btn btn-primary">Войти</button>
        </form>
    </div>
</template>