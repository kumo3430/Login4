<div>
  <!-- <slot>習慣週期</slot> -->
  <div class="my-4">
      <label class="formLabel"
          >運動目標：</label
      >
      <div class="grid grid-cols-9 gap-4">
          <select
              name="type"
              id="type"
               @if($todoId) disabled @endif
              class="col-span-4 p-2 form-input"
          >
              <option value="0"></option>
              <option value="1">跑步</option>
              <option value="2">單車騎行</option>
              <option value="3">散步</option>
              <option value="4">游泳</option>
              <option value="5">爬樓梯</option>
              <option value="6">健身</option>
              <option value="7">瑜伽</option>
              <option value="8">舞蹈</option>
              <option value="9">滑板</option>
              <option value="10">溜冰</option>
              <option value="11">滑雪</option>
              <option value="12">跳繩</option>
              <option value="13">高爾夫</option>
              <option value="14">網球</option>
              <option value="15">籃球</option>
              <option value="16">足球</option>
              <option value="17">排球</option>
              <option value="18">棒球</option>
              <option value="19">曲棍球</option>
              <option value="20">羽毛球</option>
              <option value="21">劍道</option>
              <option value="22">拳擊</option>
              <option value="23">柔道</option>
              <option value="24">跆拳道</option>
              <option value="25">柔術</option>
              <option value="26">舞劍</option>
              <option value="27">團體健身課程</option>
          </select>
          <input
              type="number"
              name="value"
              class="col-span-2 p-2 form-input"
               @if($todoId) disabled @endif
          />
          <select
              name="goalUnit"
              id="goalUnit"
               @if($todoId) disabled @endif
              class="p-2 col-span-3 form-input"
          >
              <!-- TODO 希望可以依照運動類別去顯示其他選項 -->
              <option value="0"></option>
              <option value="1">次</option>
              <option value="2">小時</option>
              <option value="3">卡路里</option>
          </select>
      </div>
  </div>
</div>