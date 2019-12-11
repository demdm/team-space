export default (state = [], action) => {
    switch (action.type) {
        case 'SET_ID':
            state.id = action.id;

            return state;
        default:
            return state;
    }
};