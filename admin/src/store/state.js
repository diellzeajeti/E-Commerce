const state = {
    user: {
        token: sessionStorage.getItem('TOKEN'),
        data: {}
    },
    products:{
         loading:false,
         date: [],
    }
};

export default state;