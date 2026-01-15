<script setup>
import { reactive } from 'vue';

    const form = reactive({
        email: '',
        password: ''
    })
    const handleLogin = async () => {
       
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