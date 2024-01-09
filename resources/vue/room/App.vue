<script>
import axios from 'axios';
import Header from '../Header.vue';
import Footer from '../Footer.vue';
import CopyRight from '../CopyRight.vue';
import VueQrcode from '@chenfengyuan/vue-qrcode';

export default {
	components: {
		Header,
		Footer,
		CopyRight,
		VueQrcode,
	},
	data: () => ({
		url: location.href,
		rootPath: '',
		isLoading: true,
		modeView: 0,
		rooms: [],
		playersOnRoom: [],
		errors: [],
		form: {
			selection: {
				sex: ['male', 'fmale'],
				imgs: [1,2,3,4,5,6,7,8,9],
				works:[],
			},
			player: {
				id: 0,
				roomid: 0,
				sex: 'male',
				imgSelected: 0,
				img: 1,
				name: '',
				workidSelected: 0,
				workid: 1,
				pass1: '',
				pass2: '',
				color: 'white',
			},
			copyright:{
				displayDialog: false,
			},
		},
		create: {
			flg: false,
			periodTurnItems: [5, 6, 7, 8, 9, 10],
			playerNumItems: [1, 2, 3, 4, 5],
			form: {
				step: 1,
				roomName: '',
				playerNum: 1,
				periodTurn: 5,
			}, //カンマを追加
			error:'',
		},
		confirmRemoveRoom:{
			show: false,
			room: {},
			message: '',
		},
	}),
	created: function () {
		if(this.url.indexOf('/room') != -1){
			this.rootPath = this.url.split('/room')[0];
		}
		this.loadRooms();
		this.loadWorks();
	},
	mounted() {
		window.onload = () => {
		};
	},
	methods: {
		loadRooms() {
			this.isLoading = true;
			this.modeView = 0;
			this.form.player.roomid = 0;
			this.errors = [];
			axios
				.get(this.rootPath + '/api/v1/room/getAll', this.param)
				.then((response) => {
					this.isLoading = false;
					try {
						if(response.data.error != undefined){
							this.errors = response.data.error.errorInfo;
						}else if(response.data.rooms  != undefined){
						//app.rooms = response.data.rooms;
							this.rooms = response.data.rooms;
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
		},
		loadWorks() {
			this.form.selection.works = [];
			this.errors = [];
			axios
				.get(this.rootPath + '/api/v1/work/getAll', this.param)
				.then((response) => {
					try {
						if(response.data.error != undefined){
							this.errors = response.data.error.errorInfo;
						}else if(response.data.works  != undefined){
						//app.rooms = response.data.rooms;
							this.form.selection.works = response.data.works;
							this.form.selection.works.push({id:0, type:'ランダム', img:'random', salary:'?', lifelevelMin:'?', lifelevelMax:'?' });
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
		},
		showFormCreateRoom(){
			this.create.flg=true; 
			this.create.form.step=1;
			this.create.form.roomName='';
			this.create.form.playerNum=1;
			this.create.form.periodTurn=5;
		},
		createRoom(){
			this.create.form.step = 3;
			axios
			.post(this.rootPath + '/api/v1/room/create', {
				parameter: this.create.form,
			})
			.then((response) => {
				if(response.data.code == 0){
					this.create.form.step = 4;
				}else{
					this.create.form.step = 2;
					this.create.error = response.data.error;
				}
			})
			.catch((err) => {
				this.create.error = err.message;
				this.create.form.step = 2;
			});
		},
		removeRoom(room, confirmed){
			if(!confirmed){
				this.confirmRemoveRoom.message = '部屋「' + room.room.name + '」を削除します。よろしいですか？';
				if(room.players[0].turn > 1){
					this.confirmRemoveRoom.message = '部屋「' + room.room.name + '」はプレイ中で、現在' + room.players[0].turn + 'ターン目のようです。本当に削除しますか？';
				}
				this.confirmRemoveRoom.room = room.room;
				this.confirmRemoveRoom.show = true;
				return;
			}
			axios
			.delete(this.rootPath + '/api/v1/room/remove', {
				data: {
					roomid: room.id,
				},
			})
			.then((response) => {
				if(response.data.code == 0){
					this.confirmRemoveRoom.show = false;
					this.loadRooms();
				}else{
					this.confirmRemoveRoom.message = 'エラーが発生しました。エラーコード：' + response.data.code;
				}
			})
			.catch((err) => {
				this.confirmRemoveRoom.message = 'エラーが発生しました。エラー：' + err.message;
			});
		},
		entryRoom(room){
			this.playersOnRoom = room.players;
			this.form.player.roomid = room.room.id;
			this.modeView = 1;
		},
		playGame(player){
			if(player.name == ''){
				player.sex = 'male';
				player.imgSelected = 0;
				player.workidSelected = 0;
				player.pass = '';
				this.form.player = player;
				this.modeView = 2;
			}else{
				location.href = this.rootPath + '/play/' + player.id;
			}
		},
		checkName(){
			this.errors = [];
			if(this.form.player.name == ''){
				this.errors.push('名前を入力してください。')
			}
			else if(this.form.player.name.length > 5){
				this.errors.push('お名前は5文字以内にしてください。');
			}
			if(this.errors.length > 0){

			}
		},
		makePlayer(){
			this.checkName();
			if(this.form.player.pass == ''){
				this.errors.push('パスワードを入力してください。')
			}

			if(this.errors.length == 0){
				this.form.player.img = this.form.selection.imgs[this.form.player.imgSelected];
				this.form.player.workid = this.form.selection.works[this.form.player.workidSelected].id;
				axios
				.post(this.rootPath + '/api/v1/player/create', this.form.player)
				.then((response) => {
					try {
						if(response.data.player != undefined){
							this.form.player = response.data.player;
							this.form.player.roomid = 0;
							this.modeView = 3;
						}
						else if(response.data.error != undefined){
							this.errors = response.data.error.errorInfo;
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
			}
		},
		nextStep() {
			console.log(this.create);
			this.create.error = '';
			if (this.create.form.roomName == '') {
				this.create.error = '部屋名を入力してください。';
			} else if (!this.checkMaxLength(this.create.form.roomName)) {
				this.create.error = '15文字以内で入力ください';
			}

			if (this.create.error == '') {
				this.create.form.step = 2;
			}
		},
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
  @import url('https://fonts.googleapis.com/css2?family=Kosugi+Maru&family=Rajdhani:wght@500&family=Shrikhand&display=swap');
</style>
<style lang="scss" scoped>
@import '../../scss/app.scss';
@import '../../scss/index.scss';
</style>
<style>
.step{
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
	position: absolute;
	transform-origin-y: 0px;
}
.error {
	font-size: smaller;
	color: red;
}
</style>
<template>
	<header>
		<Header></Header>
	</header>
	<v-card v-bind:class="[create.flg==0 ? 'scaleShow' : 'scaleHide']">
		<div style="margin-top:10px;">
			<v-btn
				@click="loadRooms()"
				>部屋再読み込み</v-btn
			>
			<v-btn
				@click="showFormCreateRoom()"
			>
				部屋を作成する
			</v-btn>
		</div>
		<v-container>
			<!-- 部屋一覧-->
			<v-row v-for="room in rooms" :key="id" v-bind:class="[!isLoading ? 'scaleShow' : 'scaleHide']">
				<v-col
					style="
						padding: 10px;
						border-bottom: solid thin gray;
					"
				>
					<div style="float: left">
						{{ room.room.name }}
					</div>
					<div style="float: right;">
						<span
						v-if="this.form.player.roomid == room.room.id"
						style="border-radius:5px; padding:5px; background-color:lightpink; color:white;"
						>
							入室中
						</span>
						<v-btn v-else @click="entryRoom(room)">入室</v-btn>
						<v-btn @click="removeRoom(room, false)">削除</v-btn>
					</div>
					<br style="clear: left" />
				</v-col>
			</v-row>
			<div v-bind:class="[isLoading ? 'scaleShow' : 'scaleHide']">
				<v-progress-circular color="blue-lighten-3" model-value="20" :size="77"></v-progress-circular>
				loading data...
			</div>
		</v-container>


		<!-- Entry Room -->
		<div v-bind:class="[modeView == 1 ? 'scaleShow' : 'scaleHide']" style="margin:10px;">
			<v-container>
				<v-row style="margin: 0 10;">
					プレイするプレイヤーを選んでください。
				</v-row>
				<v-row v-for="player in playersOnRoom" :key="id">
					<v-col
						style="
							border-bottom:solid thin lightgray;
							padding: 10px;
						"
					>
						<div v-if="player.name != ''">
							<div style="float: left;">
								<img 
								:src=" this.rootPath + '/image/avatar/' + player.sex + '/icon0' + player.img + '.png'" 
								class="rounded-circle"
								Width="30"
								Height="30"
								/>
							</div>
							<div style="float: left;">
								{{ player.name }}&nbsp;
							</div>
						</div>
						<div v-else>
							<div style="float: left;">
								<img 
								:src="this.rootPath + '/image/avatar/random.png'" 
								class="rounded-circle"
								Width="30"
								Height="30"
								/>
							</div>
							<div style="float: left;">
								プレイヤー未作成
							</div>
						</div>
						<div
							style="
								float: right;
							"
						>
							<v-btn @click="playGame(player)">
								<span v-if="player.name != ''">
									参加
								</span>
								<span v-else>
									プレイヤー作成
								</span>
							</v-btn>
						</div>
						<br style="clear: left" />
					</v-col>
				</v-row>
			</v-container>
		</div>

		<!-- setting Player -->
		<div v-bind:class="[modeView == 2 ? 'scaleShow' : 'scaleHide']" style="margin:10px;">
			<div>
				プレイヤーの設定をしてください。
			</div>
			<v-text-field
				label="お名前"
				v-model="this.form.player.name"
				v-on:change="checkName"
				placeholder="お名前を入力してください。"
			>
			</v-text-field>
			<v-text-field
				label="パスワード"
				type="password"
				v-model="this.form.player.pass"
				hint="Enter your password to access this website"
			></v-text-field>
			<div v-for="n in 10" style="float:left; margin:5px;">
				<v-btn @click="this.form.player.pass += (n%10) + ''">{{ n%10 }}</v-btn>
			</div>
			<v-spacer></v-spacer>
			<div style="float:left; margin:5px;">
				<v-btn @click="this.form.player.pass = ''">Clear</v-btn>
			</div>
			<br style="clear: both;" />
			<v-select
				label="性別"
				v-model="form.player.sex"
				:items="form.selection.sex"
			></v-select>
			<div style="margin: 10px;">
				アバターアイコンを選択してください。
			</div>
			<div style="width:100%; height:50%; max-height:300px; text-align:center;">
				<v-carousel 
				:continuous="false"
				v-model="form.player.imgSelected"
				width="200"
				height="200"
				class="rounded-circle"
				hide-delimiters
				>
					<v-carousel-item
						v-for="img in form.selection.imgs"
						contain
					>
						<img 
						:src="this.rootPath + '/image/avatar/' + form.player.sex + '/icon0' + img + '.png'" 
						class="rounded-circle"
						/>
					</v-carousel-item>
				</v-carousel>
			</div>
			<div style="margin: 10px;">
				仕事を選んで下さい。
			</div>
			<v-carousel
			:continuous="false"
			v-model="form.player.workidSelected"
			show-arrows="true"
			hide-delimiters
			height="200"
			width="400"
			>

				<v-carousel-item
						v-for="work in form.selection.works"
						:key="id"
						style="text-align:center; background-color:red;"
					>
					<v-card
						outlined
						shaped
						style="width:300px; padding:10px; margin: 0 auto; background-color: black;"
					>
					<div 
					style="float:left;">
						<img 
						width="45" 
						height="45" 
						class="rounded-circle" 
						:src="this.rootPath + '/image/work/' + work.img + '.png'" />
					</div>
					<div 
					class="btn-text-gradient--gold text-h4"
					style="float:left;">
						{{work.type}}
					</div>

					<div 
						style="width:270px;"
						class="btn-border-gradient-wrap btn-border-gradient-wrap--gold"
						>
							<a 
							href="javascript:void(0);" 
							style="width:260px; font-size:smaller;"
							class="btn btn-border-gradient">
								<div class="btn-text-gradient--gold">
									給料：{{ work.salary }}
								</div>
								<div class="btn-text-gradient--gold">
									生活水準:{{ work.lifelevelMin }} ～ {{ work.lifelevelMax }}
									<br>
									※初期状態：{{ work.lifelevelMin }}
								</div>
							</a>
						</div>
						<br style="clear:both;" />
					</v-card>
				</v-carousel-item>
			</v-carousel>
			<div style="maring-top:10px;" />
			<div style="text-align:center; margin:auto;">
				<v-btn
				class="text-h4"
				elevation="30"
				height="60"
				rounded
				color="deep-purple darken-1"
				@click="makePlayer()">これで決まり！！</v-btn>
			</div>
		</div>

		<!-- created Player -->
		<div v-bind:class="[modeView == 3 ? 'scaleShow' : 'scaleHide']">
			<v-card>
				<v-card-title
				color="primary"
				dark>
				プレイヤー作成完了
				</v-card-title>
				<v-card-text>
					<img 
						:src="this.rootPath + '/image/avatar/' + form.player.sex + '/icon0' + form.player.img + '.png'" 
						class="rounded-circle"
						/>
					<div>
						{{this.form.player.name}}さん、ようこそ！
					</div>
				</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn @click="playGame(this.form.player)">
						ゲームに参加
					</v-btn>
				</v-card-actions>
			</v-card>
		</div>

		<!-- Error -->
		<ul class="error">
			<li v-for="error in this.errors">{{ error }}</li>
		</ul>
	</v-card>
	<v-card v-bind:class="[create.flg==1 ? 'scaleShow' : 'scaleHide']"
		style="text-align: center;"
		height="400">
		<div class="step" v-bind:class="[create.form.step==1 ? 'scaleShow' : 'scaleHide']">
			ゲームルームの設定をしてください。
			<div>
				<v-text-field
					id="roomName"
					label="部屋名"
					v-model="create.form.roomName"
					placeholder="部屋名を入力してください"
				>
				</v-text-field>
			</div>
			<div>
				<v-select
					id="playerNum"
					label="プレイヤー数"
					v-model="create.form.playerNum"
					:items="create.playerNumItems"
				></v-select>
			</div>
			<div>
				<v-select
					id="periodTurn"
					label="決算ターン"
					v-model="create.form.periodTurn"
					:items="create.periodTurnItems"
				></v-select>
			</div>
			<v-btn
			@click="create.flg = 0; this.loadRooms();"
			>
			戻る
			</v-btn>
			<v-btn
				@click="nextStep()">
				次へ
			</v-btn>
		</div>
		<div class="step" v-bind:class="[create.form.step==2 ? 'scaleShow' : 'scaleHide']">
			<br style="margin:5px;" />
			<div v-if="create.form.step==2" class="btn-border-gradient-wrap btn-border-gradient-wrap--gold">
				<a 
				href="javascript:void(0);" 
				style="width:400px; font-size:smaller;"
				class="btn btn-border-gradient">
					<h2 
					class="btn-text-gradient--gold"
					>
						これでいいですか？
					</h2>
					<div class="btn-text-gradient--gold">
						部屋名:{{ create.form.roomName }}
					</div>
					<div class="btn-text-gradient--gold">
						プレイヤー数:{{ create.form.playerNum }}
					</div>
					<div class="btn-text-gradient--gold">
						決算ターン:{{ create.form.periodTurn }}
					</div>
				</a>
			</div>
			<br style="clear:both;" />
			<v-btn
				@click="create.form.step=1"
				>考え直す</v-btn>
			<v-btn
				@click="createRoom()"
				>部屋を作成</v-btn>
		</div>
		<div class="step" v-bind:class="[create.form.step==3 ? 'scaleShow' : 'scaleHide']">
			部屋作ってます。ちょっと待ってね。。
		</div>
		<div class="step" v-bind:class="[create.form.step==4 ? 'scaleShow' : 'scaleHide']">
			<div>部屋ができました！</div>
			<div>ボタンを押して、作った部屋でプレイヤーを設定しましょう。</div>
			<v-btn
			@click="create.flg = 0; this.loadRooms();"
			>
			部屋一覧を表示する
			</v-btn>
		</div>
		<div class="error">
			{{ create.error }}
		</div>
	</v-card>

	<!--確認ダイアログ-->
	<v-dialog 
	v-model="this.confirmRemoveRoom.show"
	transition="dialog-top-transition"
	max-width="400"
	>
		<v-card width="320" height="400">
			<v-card-text style="overflow-y: auto;">
				{{ this.confirmRemoveRoom.message }}
			</v-card-text>
			<v-card-actions>
				<v-spacer></v-spacer>
				<v-btn
				color="blue-darken-1"
				variant="text"
				@click="removeRoom(this.confirmRemoveRoom.room, true);"
				>
					はい
				</v-btn>
				<v-btn
				color="blue-darken-1"
				variant="text"
				@click="this.confirmRemoveRoom.show=false;"
				>
					いいえ
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
	<VueQrcode :value="url" :options="{ width: 200 }" />
	<!--Notifyダイアログ-->
	<v-dialog 
	v-model="this.form.copyright.displayDialog"
	transition="dialog-top-transition"
	max-width="400"
	>
		<v-card width="320" height="400">
			<v-card-text style="overflow-y: auto;">
				<div style="font-size:smaller;">
					<CopyRight></CopyRight>
				</div>
			</v-card-text>
			<v-card-actions>
				<v-spacer></v-spacer>
				<v-btn
				color="blue-darken-1"
				variant="text"
				@click="this.form.copyright.displayDialog = false;"
				>
					閉じる
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
	<footer @click="this.form.copyright.displayDialog = true;">
		<Footer></Footer>
	</footer>
</template>
