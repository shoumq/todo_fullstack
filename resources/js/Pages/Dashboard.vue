<script setup>
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <header>
        <div class="container flex-header">
            <div class="title">{{ user.name }}</div>
            <div class="logout" @click="logout">Выйти</div>
        </div>
    </header>

    <main class="container">
        <div class="main-header">
            <div class="title">Список дел ({{ list.length }})</div>
        </div>

        <div class="main-main">
            <form class="add-item" @submit.prevent="addItem">
                <input type="text" placeholder="Название" v-model="titleItem">
                <button class="btn btn-purple">Добавить</button>
            </form>

            <div class="list">
                <div class="list-item">
                    <div class="list-item__flex-jus" v-for="item in list">
                        <div class="list-item__flex">
                            <input type="checkbox" :checked="item.status === '1'" value="1" @change="changeStatus(item.id)">
                            <div style="font-size: 20rem;">{{ item }}</div>
                            <div class="title" v-if="!item.edit">{{ item.title }}</div>
                            <form @submit.prevent="closeEdit(item.id)">
                                <input type="text" v-model="item.title" v-if="item.edit">
                            </form>
                        </div>

                        <div class="list-item__flex">
                            <button class="btn btn-primary" @click="addEditItem(item.id)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-pencil-square"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                </svg>
                            </button>
                            <button class="btn btn-danger" @click="delItem(item.id)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="white" class="bi bi-trash3"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>


<script>

export default {
    data() {
        return {
            titleItem: ''
        }
    },

    props: ['user', 'list'],

    methods: {
        logout() {
            axios.post('/logout')
            window.location.href = '/login'
        },

        delItem(item_id) {
            axios.post('/del_item', { item_id })
                .then(() => {
                    this.list.splice(this.list.findIndex(x => x.id === item_id), 1)
                })
        },

        addItem() {
            axios.post('/add_item', { titleItem: this.titleItem })
                .then((response) => {
                    this.list.unshift(response.data)

                    for (let i = 0; i < this.list.length; i++) {
                        this.list[i].edit = false;
                    }

                    this.titleItem = ''
                })
        },

        addEditItem(item_id) {
            for (let i = 0; i < this.list.length; i++) {
                this.list[i].edit = false;
            }

            this.list.find(x => x.id === item_id).edit = 1
        },

        closeEdit(item_id) {
            axios.post('/save_item', { item_id, title: this.list.find(x => x.id === item_id).title })

            for (let i = 0; i < this.list.length; i++) {
                this.list[i].edit = false;
            }
        },

        changeStatus(item_id) {
            if (this.list.find(x => x.id === item_id).status === '1') {
                this.list.find(x => x.id === item_id).status = '0'
            } else {
                this.list.find(x => x.id === item_id).status = '1'
            }

            axios.post('/status_item', { status: this.list.find(x => x.id === item_id).status, item_id })
                .then((response) => {
                    console.log(response);
                })
        }
    },

    mounted() {
        for (let i = 0; i < this.list.length; i++) {
            this.list[i].edit = false;
        }
    }
}
</script>
