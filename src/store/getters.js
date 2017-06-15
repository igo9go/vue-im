const getters = {
    filterUser: state => state.chat.filterUser,
    currentUser: state => state.chat.currentUser,
    users: state => state.chat.users,
    currentSession: state => state.chat.currentSession,
    broadcast: state => state.chat.broadcast,
    conn: state => state.chat.connection,
    online: state => state.chat.online,
    currentCount: state => state.chat.currentCount,
    notice: state => state.chat.notice
}


export default getters
