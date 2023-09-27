<script>
export default {
	data() {
		return {
			periodTurnItems: [5, 6, 7, 8, 9, 10],
			playerNumItems: [1, 2, 3, 4, 5],
			form: {
				step: 1,
				roomName: '',
				playerNum: 1,
				periodTurn: 5,
			}, //カンマを追加
			error:'',
		};
	},
	methods: {
		next() {
			this.error = '';
			if (this.form.roomName == '') {
				this.error = '部屋名を入力してください。';
			} else if (!this.checkMaxLength(this.form.roomName)) {
				this.error = '15文字以内で入力ください';
			}

			if (this.error == '') {
				this.form.step = 2;
			}
		},
		createRoom(){
			this.form.step = 3;
			axios
			.post('/api/v1/room/create', {
				parameter: this.form,
			})
			.then((response) => {
				if(response.data.code == 0){
					this.form.step = 4;
				}else{
					this.form.step = 2;
					this.error = response.data.error;
				}
			})
			.catch((err) => {
				this.error = err.message;
				this.form.tep = 2;
			});
		},
		checkForm() {},
		checkString(inputdata) {
			var regExp = /^[a-zA-Z0-9_]*$/;
			return regExp.test(inputdata);
		},
		checkMaxLength(inputdata) {
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
.step{
	position: absolute;
	width: 100%;
}
.scaleShow{
	transition: all 0.5s 0s ease;
	zoom:1.0;
}
.scaleHide{
	transition: all 0.5s 0s ease;
	zoom:1.0;
	transform:scale(0.0, 1.0);
	transform-origin: 0px 0px;
}
.error {
	font-size: smaller;
	color: red;
}
</style>
<template>
	<v-card
		style="text-align: center;"
		height="300">
		<div class="step" v-bind:class="[this.form.step==1 ? 'scaleShow' : 'scaleHide']">
			ゲームルームの設定をしてください。
			<div>
				<v-text-field
					id="roomName"
					label="部屋名"
					v-model="form.roomName"
					v-on:change="checkForm"
					placeholder="部屋名を入力してください"
				>
				</v-text-field>
			</div>
			<div>
				<v-select
					id="playerNum"
					label="プレイヤー数"
					v-model="form.playerNum"
					:items="playerNumItems"
				></v-select>
			</div>
			<div>
				<v-select
					id="periodTurn"
					label="決算ターン"
					v-model="form.periodTurn"
					:items="periodTurnItems"
				></v-select>
			</div>
			<v-btn
				class="mx-4 white--text"
				elevation="2"
				x-large
				rounded
				color="deep-purple darken-1"
				@click="next()">
				次へ
			</v-btn>
		</div>
		<div class="step" v-bind:class="[this.form.step==2 ? 'scaleShow' : 'scaleHide']">
			<div>
				これでいいですか？
			</div>
			<div>部屋名:{{ form.roomName }}</div>
			<div>プレイヤー数:{{ form.playerNum }}</div>
			<div>決算ターン:{{ form.periodTurn }}</div>
			<v-btn
				class="mx-4 white--text"
				elevation="2"
				x-large
				rounded
				color="deep-purple darken-1"
				@click="this.form.step=1"
				>考え直す</v-btn>
			<v-btn
				class="mx-4 white--text"
				elevation="2"
				x-large
				rounded
				color="deep-purple darken-1"
				@click="createRoom()"
				>部屋を作成</v-btn>
		</div>
		<div class="step" v-bind:class="[this.form.step==3 ? 'scaleShow' : 'scaleHide']">
			部屋作ってます。ちょっと待ってね。。
		</div>
		<div class="step" v-bind:class="[this.form.step==4 ? 'scaleShow' : 'scaleHide']">
			部屋できたよ。<br />
			作った部屋でプレイヤーを設定しよう。
			<v-btn
			class="mx-4 white--text"
			elevation="2"
			x-large
			rounded
			color="deep-purple darken-1"
			@click="location.href='/room/'"
			>部屋一覧へ</v-btn>
			<a href="/room/">部屋一覧へ</a>
		</div>
	</v-card>
	<div class="error">
		{{ this.error }}
	</div>
	<Transition name="fade">
		<router-view></router-view>
	</Transition>
</template>
