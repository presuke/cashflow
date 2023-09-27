<style lang="scss" scoped>
@import '../../../scss/play/score.scss';
</style>
<template>
    <v-row v-for="player in $parent.players" 
    v-bind:class="[player.id == $parent.crntPlayer.id ? 'activePlayer' : 'passivePlayer']"
    class="rowPlayer">
        <v-col>
            <div v-bind:class="[player.flgFire == '1' ? 'scaleShow' : 'scaleHide']" class="fire">FIRE ZONE</div>
            <div class="player">
                <img 
                :src="'/image/avatar/' + player.sex + '/icon0' + player.img + '.png'" 
                v-bind:class="[player.stress <= 3 ? 'fine' : '',
                               player.stress > 3 && player.stress <= 6 ? 'caution' : '',
                               player.stress > 6 && player.stress <= 9 ? 'denger' : '',
                               player.stress > 9 && player.stress <= 12 ? 'out' : '',
                               player.stress > 12 ? 'die' : '',]"
                class="rounded-circle icon"
                />
                <div style="float:left;">
                    <div class="name">{{ player.name }}</div>
                    <div class="turn">{{ player.turn }} turn</div>
                </div>

                <div class="work">
                    <img 
                    :src="'/image/work/' + player.work.img + '.png'"
                    class="rounded-circle icon" />
                    {{ player.work.type }}
                    <div class="salary">
                        <img 
                        :src="'/image/status/salary.svg'"
                        class="rounded-circle icon" />
                        <div>
                            {{ player.work.salary.toLocaleString() }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="status">
                <div style="clear:left; padding-top:10px; font-size:min(4vw, 20px);">
                    <div style="margin-right:10px; float:left; font-family: 'Kosugi Maru', sans-serif;">
                        生活水準
                    </div>
                    <div style="float:left; font-family: 'Shrikhand', cursive;">
                        <span v-if="player.lifelevel <= 2">
                            Very Low
                        </span>
                        <span v-else-if="player.lifelevel <= 4">
                            Low
                        </span>
                        <span v-else-if="player.lifelevel <= 6">
                            Middle
                        </span>
                        <span v-else-if="player.lifelevel <= 8">
                            High
                        </span>
                        <span v-else="">
                            Gorgeous
                        </span>
                    </div>
                </div>
                <div style="clear:left;">
                    <div class="parameter">
                        <img 
                        src="/image/status/money.svg" 
                        class="icon" 
                        style="filter: drop-shadow(2px 2px 2px #990);"
                        />
                        {{ $parent.scores[player.id + ''].money.toLocaleString() }}&nbsp;
                    </div>
                    <div class="parameter">
                        <img 
                        src="/image/status/stock.svg" 
                        class="icon" 
                        style="filter: drop-shadow(2px 2px 2px #66f);"
                         />
                        {{ $parent.scores[player.id + ''].stock.toLocaleString() }}&nbsp;
                    </div>
                    <div class="parameter"
                    v-bind:class="[$parent.scores[player.id + ''].loan > 0 ? 'valuePlus' : '',]">
                        <img 
                        src="/image/status/bank.svg" 
                        class="icon" 
                        style="filter: drop-shadow(2px 2px 2px #c33);"
                        />
                        {{ ($parent.scores[player.id + ''].loan * -1).toLocaleString() }}&nbsp;
                    </div>
                    <div class="parameter">
                        <img 
                        src="/image/status/estate.svg" 
                        class="icon" 
                        style="filter: drop-shadow(2px 2px 2px #393);"
                        />
                        {{ $parent.scores[player.id + ''].estate.toLocaleString() }}&nbsp;
                    </div>
                </div>
            </div>
            <!--
        -->
        </v-col>
    </v-row>
    <v-card style="display: none;">
        {{ $parent.scores }}
    </v-card>
</template>