<script>
export default {
	data: () => ({
		isLoading: true,
		nextReflesh: '',
		token: '',
		room: {},
		players: [],
		errors: [],
		reflexhInterval: 1000,
	}),
	created: function () {
		this.reflesh();
	},
	methods: {
		reflesh() {
			//認証
			if(this.auth()){
				//部屋の状況を取得
				axios
				.get('/api/v1/play/getRoomStatus', this.query)
				.then((response) => {
					this.isLoading = false;
					try {
						if(response.data.error != undefined){
							this.errors = response.data.error.errorInfo;
						}else if(response.data.room  != undefined){
						//app.rooms = response.data.rooms;
							this.rooms = response.data.room;
						}else{
							this.errors = '特定できないエラー';
						}
					} catch (e) {
						this.errors = e;
					}
				})
				.catch((err) => {
					console.log(err);
				});
			}else{
				alert('un authed');
			}
		},
		auth(){
			return true;
		},
		checkForm: function () {},
		checkString: function (inputdata) {
			var regExp = /^[a-zA-Z0-9_]*$/;
			return regExp.test(inputdata);
		},
		checkMaxLength: function (inputdata) {
			var booleanLength = false;
			inputdata.length <= 15
				? (booleanLength = true)
				: (booleanLength = false);
			return booleanLength;
		},
	},
};
</script>
<style>
.error {
	font-size: smaller;
	color: red;
}
</style>
<template>
	状況表示
</template>
