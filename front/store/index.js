export const state = () => ({

}) 

export const mutations = {
    setUser (state, payload) {
        state.auth.user = payload
    },
    setUserAvatar (state, payload) {
        state.auth.user.avatar = payload
    },
    setUserCover (state, payload) {
        state.auth.user.cover = payload
    }
}

export const actions = {
    setUser (context, payload) {
        context.commit('setUser', payload)
    },
    setUserAvatar (context, payload) {
        context.commit('setUserAvatar', payload)
    },
    setUserCover (context, payload) {
        context.commit('setUserCover', payload)
    }
}

export const getters = {
    authenticated: (state) => {
        return state.auth.loggedIn
    },
    user: (state) => {
        return state.auth.user
    }
}