<script>
import axios from 'axios';
export default {
	data: function () {
		return {
			param: {
				roomName: '',
				playerNum: 0,
			},
			form: {
				isShowBtn: true,
				postResult: '送信すると結果を表示',
			},
		};
	},
	created: function () {
		this.param = this.$route.query;
	},
	methods: {
		post: function () {
			axios
				.post('/api/v1/room/create', this.param)
				.then((response) => {
					this.form.isShowBtn = false;
					this.form.postResult = 'いいわねぇ～';
				})
				.catch((err) => {
					this.form.isShowBtn = false;
					this.form.postResult = 'だめねぇ～';
				});
		},
	},
};
</script>
<template>
	<h2>confirm</h2>
	<div>
		<div>部屋名:{{ param.roomName }}</div>
		<div>プレイヤー数:{{ param.playerNum }}</div>
		<v-btn
			class="mx-4 white--text"
			elevation="2"
			x-large
			rounded
			color="deep-purple darken-1"
			@click="post"
			v-if="form.isShowBtn"
			>部屋を作成</v-btn
		>
		<div v-if="!form.isShowBtn">{{ form.postResult }}</div>
	</div>
</template>
