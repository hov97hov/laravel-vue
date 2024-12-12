import { createStore } from 'vuex';
import axios from "axios";

const store = createStore({
    state: {
        posts: [],
    },
    mutations: {
        setPosts(state) {
            state.posts = state ;
        },
    },
    actions: {
        getPosts({ commit }, ids) {
            axios.get(`get-all-posts`)
                .then(res => {
                    commit('setPosts', res.data);
                })
                .catch(err => console.log(err));
        },
    },
    getters: {
        posts: (state) => state.posts,
    },
});

export default store;
