@if( $module === "edit")
    <div class="form-group required">
        <label for="form-update-room-image" class="col-sm-3 control-label">Theme0</label>
        <div class="col-sm-2">
            <img id="form-update-room-image-img" src="" alt="" class="img-thumbnail" style="max-width: 128px; max-height: 128px;cursor: pointer;" onclick="showBigIconImageModal(window.$('#form-update-room-name').val(), this.src);">
            <img id="form-update-room-theme-thumbnail-0-img" src="" alt="" class="img-thumbnail" style="max-width: 128px; max-height: 128px;cursor: pointer;" onclick="showBigIconImageModal(window.$('#form-update-room-name').val(), this.src);">
        </div>
        <div class="col-sm-4">
            <label for="form-update-room-image" class="file-label"><b>Upload Theme 0</b></label>
            <input type="hidden" name="theme0" id="form-update-room-chosen-theme-0" class="form-control">
            <input type="file" name="image" id="form-update-room-image" accept="image/*" class="form-control" onchange="handleFileChange('form-update-room-image','form-update-room-chosen-theme-0')">
            <br>

            <label for="form-update-room-theme-thumbnail-0" class="file-label"><b>Upload Thumbnail 0</b></label>
            <input type="hidden" id="form-update-room-chosen-thumbnail-0" class="form-control">
            <input type="file" name="theme_thumbnail0" id="form-update-room-theme-thumbnail-0" accept="image/*" class="form-control" onchange="handleFileChange('form-update-room-theme-thumbnail-0','form-update-room-chosen-thumbnail-0')">
            <br>

            <label for="form-update-room-text-0" class="file-label"><b>Theme Name 0</b></label>
            <input type="text" name="text0" id="form-update-room-text-0" class="form-control" placeholder="Enter Theme0 Name here" required>
        </div>
        
        @if (!config('app.unlimited_image_size'))
            <span class="col-sm-3 help-block">Image must be less than 4 MB and resolution less than 4096x4096 pixels.</span>
        @endif
    </div>

    <div class="form-group">
        <label for="form-update-room-theme-1" class="col-sm-3 control-label">Theme 1</label>
        <div class="col-sm-2">
            <img id="form-update-room-theme-1-img" src="" alt="" class="img-thumbnail" style="max-width: 128px; max-height: 128px;cursor: pointer;" onclick="showBigIconImageModal(window.$('#form-update-room-name').val(), this.src);">
            <img id="form-update-room-theme-thumbnail-1-img" src="" alt="" class="img-thumbnail" style="max-width: 128px; max-height: 128px;cursor: pointer;" onclick="showBigIconImageModal(window.$('#form-update-room-name').val(), this.src);">
        </div>
        <div class="col-sm-4">
            <label for="form-update-room-theme-1" class="file-label"><b>Upload Theme 1</b></label>
            <input type="hidden" id="form-update-room-chosen-theme-1" class="form-control">
            <input type="file" name="theme1" id="form-update-room-theme-1" accept="image/*" class="form-control" onchange="handleFileChange('form-update-room-theme-1','form-update-room-chosen-theme-1')">
            <br>

            <label for="form-update-room-theme-thumbnail-1" class="file-label"><b>Upload Thumbnail 1</b></label>
            <input type="hidden" id="form-update-room-chosen-thumbnail-1" class="form-control">
            <input type="file" name="theme_thumbnail1" id="form-update-room-theme-thumbnail-1" accept="image/*" class="form-control" onchange="handleFileChange('form-update-room-theme-thumbnail-1','form-update-room-chosen-thumbnail-1')">
            <br>

            <label for="form-update-room-text-1" class="file-label"><b>Theme Name 1</b></label>
            <input type="text" name="text1" id="form-update-room-text-1" class="form-control" placeholder="Enter Theme1 Name here">

            <br>
            <button type="button" class="btn btn-default btn-sm clear-btn-wrapper pull-right" id="clear-theme-1">Clear Theme 1</button>
        
        </div>
        @if (!config('app.unlimited_image_size'))
            <span class="col-sm-3 help-block">Image must be less than 4 MB and resolution less than 4096x4096 pixels.</span>
        @endif
    </div>

    <div class="form-group">
        <label for="form-update-room-theme-2" class="col-sm-3 control-label">Theme 2</label>
        <div class="col-sm-2">
            <img id="form-update-room-theme-2-img" src="" alt="" class="img-thumbnail" style="max-width: 128px; max-height: 128px;cursor: pointer;" onclick="showBigIconImageModal(window.$('#form-update-room-name').val(), this.src);">
            <img id="form-update-room-theme-thumbnail-2-img" src="" alt="" class="img-thumbnail" style="max-width: 128px; max-height: 128px;cursor: pointer;" onclick="showBigIconImageModal(window.$('#form-update-room-name').val(), this.src);">
        </div>
        <div class="col-sm-4">
            <label for="form-update-room-theme-2" class="file-label"><b>Upload Theme 2</b></label>
            <input type="hidden" id="form-update-room-chosen-theme-2" class="form-control">
            <input type="file" name="theme2" id="form-update-room-theme-2" accept="image/*" class="form-control" onchange="handleFileChange('form-update-room-theme-2','form-update-room-chosen-theme-2')">
            <br>

            <label for="form-update-room-theme-thumbnail-2" class="file-label"><b>Upload Thumbnail 2</b></label>
            <input type="hidden" id="form-update-room-chosen-thumbnail-2" class="form-control">
            <input type="file" name="theme_thumbnail2" id="form-update-room-theme-thumbnail-2" accept="image/*" class="form-control" onchange="handleFileChange('form-update-room-theme-thumbnail-2','form-update-room-chosen-thumbnail-2')">

            <br>
            <label for="form-update-room-text-2" class="file-label"><b>Theme Name 2</b></label>
            <input type="text" name="text2" id="form-update-room-text-2" class="form-control" placeholder="Enter Theme2 Name here">

            <br>
            <button type="button" class="btn btn-default btn-sm clear-btn-wrapper pull-right" id="clear-theme-2">Clear Theme 2</button>
        </div>
        @if (!config('app.unlimited_image_size'))
            <span class="col-sm-3 help-block">Image must be less than 4 MB and resolution less than 4096x4096 pixels.</span>
        @endif
    </div>

    <div class="form-group">
        <label for="form-update-room-theme-3" class="col-sm-3 control-label">Theme 3</label>
        <div class="col-sm-2">
            <img id="form-update-room-theme-3-img" src="" alt="" class="img-thumbnail" style="max-width: 128px; max-height: 128px;cursor: pointer;" onclick="showBigIconImageModal(window.$('#form-update-room-name').val(), this.src);">
            <img id="form-update-room-theme-thumbnail-3-img" src="" alt="" class="img-thumbnail" style="max-width: 128px; max-height: 128px;cursor: pointer;" onclick="showBigIconImageModal(window.$('#form-update-room-name').val(), this.src);">
        </div>
        <div class="col-sm-4">
            <label for="form-update-room-theme-3" class="file-label"><b>Upload Theme 3</b></label>
            <input type="hidden" id="form-update-room-chosen-theme-3" class="form-control">
            <input type="file" name="theme3" id="form-update-room-theme-3" accept="image/*" class="form-control" onchange="handleFileChange('form-update-room-theme-3','form-update-room-chosen-theme-3')">
            <br>

            <label for="form-update-room-theme-thumbnail-3" class="file-label"><b>Upload Thumbnail 3</b></label>
            <input type="hidden" id="form-update-room-chosen-thumbnail-3" class="form-control">
            <input type="file" name="theme_thumbnail3" id="form-update-room-theme-thumbnail-3" accept="image/*" class="form-control" onchange="handleFileChange('form-update-room-theme-thumbnail-3','form-update-room-chosen-thumbnail-3')">

            <br>
            <label for="form-update-room-theme-3" class="file-label"><b>Theme Name 3</b></label>
            <input type="text" name="text3" id="form-update-room-text-3" class="form-control" placeholder="Enter Theme3 Name here">

            <br>
            <button type="button" class="btn btn-default btn-sm clear-btn-wrapper pull-right" id="clear-theme-3">Clear Theme 3</button>
        </div>
        @if (!config('app.unlimited_image_size'))
            <span class="col-sm-3 help-block">Image must be less than 4 MB and resolution less than 4096x4096 pixels.</span>
        @endif
    </div>

    <div class="form-group">
        <label for="form-update-room-theme-4" class="col-sm-3 control-label">Theme 4</label>
        <div class="col-sm-2">
            <img id="form-update-room-theme-4-img" src="" alt="" class="img-thumbnail" style="max-width: 128px; max-height: 128px;cursor: pointer;" onclick="showBigIconImageModal(window.$('#form-update-room-name').val(), this.src);">
            <img id="form-update-room-theme-thumbnail-4-img" src="" alt="" class="img-thumbnail" style="max-width: 128px; max-height: 128px;cursor: pointer;" onclick="showBigIconImageModal(window.$('#form-update-room-name').val(), this.src);">
        </div>
        <div class="col-sm-4">
            <label for="form-update-room-theme-4" class="file-label"><b>Upload Theme 4</b></label>
            <input type="hidden" id="form-update-room-chosen-theme-4" class="form-control">
            <input type="hidden" id="form-update-room-chosen-thumbnail-4" class="form-control">
            <input type="file" name="theme4" id="form-update-room-theme-4" accept="image/*" class="form-control" onchange="handleFileChange('form-update-room-theme-4','form-update-room-chosen-theme-4')">
            <br>

            <label for="form-update-room-theme-thumbnail-4" class="file-label"><b>Upload Thumbnail 4</b></label>
            <input type="hidden" id="form-update-room-chosen-thumbnail-4" class="form-control">
            <input type="file" name="theme_thumbnail4" id="form-update-room-theme-thumbnail-4" accept="image/*" class="form-control" onchange="handleFileChange('form-update-room-theme-thumbnail-4','form-update-room-chosen-thumbnail-4')">

            <br>
            <label for="form-update-room-theme-4" class="file-label"><b>Theme Name 4</b></label>
            <input type="text" name="text4" id="form-update-room-text-4" class="form-control" placeholder="Enter Theme4 Name here">

            <br>
            <button type="button" class="btn btn-default btn-sm clear-btn-wrapper pull-right" id="clear-theme-4">Clear Theme 4</button>
        </div>
        @if (!config('app.unlimited_image_size'))
            <span class="col-sm-3 help-block">Image must be less than 4 MB and resolution less than 4096x4096 pixels.</span>
        @endif
    </div>

    <div class="form-group">
        <label for="form-update-room-theme-5" class="col-sm-3 control-label">Theme 5</label>
        <div class="col-sm-2">
            <img id="form-update-room-theme-5-img" src="" alt="" class="img-thumbnail" style="max-width: 128px; max-height: 128px;cursor: pointer;" onclick="showBigIconImageModal(window.$('#form-update-room-name').val(), this.src);">
            <img id="form-update-room-theme-thumbnail-5-img" src="" alt="" class="img-thumbnail" style="max-width: 128px; max-height: 128px;cursor: pointer;" onclick="showBigIconImageModal(window.$('#form-update-room-name').val(), this.src);">
        </div>
        <div class="col-sm-4">
            <label for="form-update-room-theme-5" class="file-label"><b>Upload Theme 5</b></label>
            <input type="hidden" id="form-update-room-chosen-theme-5" class="form-control">
            <input type="file" name="theme5" id="form-update-room-theme-5" accept="image/*" class="form-control" onchange="handleFileChange('form-update-room-theme-5','form-update-room-chosen-theme-5')">
            <br>

            <label for="form-update-room-theme-thumbnail-5" class="file-label"><b>Upload Thumbnail 5</b></label>
            <input type="hidden" id="form-update-room-chosen-thumbnail-5" class="form-control">
            <input type="file" name="theme_thumbnail5" id="form-update-room-theme-thumbnail-5" accept="image/*" class="form-control" onchange="handleFileChange('form-update-room-theme-thumbnail-5','form-update-room-chosen-thumbnail-5')">

            <br>

            <label for="form-update-room-theme-5" class="file-label"><b>Theme Name 5</b></label>
            <input type="text" name="text5" id="form-update-room-text-5" class="form-control" placeholder="Enter Theme5 Name here">

            <br>
            <button type="button" class="btn btn-default btn-sm clear-btn-wrapper pull-right" id="clear-theme-5">Clear Theme 5</button>
        </div>
        @if (!config('app.unlimited_image_size'))
            <span class="col-sm-3 help-block">Image must be less than 4 MB and resolution less than 4096x4096 pixels.</span>
        @endif
    </div>
@else
    <div class="form-group required">
        <label for="form-room-theme-0" class="col-sm-3 control-label">Theme 0</label>
        <div class="col-sm-6">
            <input type="hidden" name="chosen_theme0" id="form-room-chosen-theme-0" class="form-control">
            <input type="file" name="image" id="form-room-image" accept="image/*" class="form-control" required onchange="handleFileChange('form-room-image','form-room-chosen-theme-0')">
            <br>

            <label for="form-room-theme-thumbnail-0" class="file-label"><b>Upload Thumbnail 0</b></label>
            <input type="hidden" id="form-room-chosen-thumbnail-0" class="form-control">
            <input type="file" name="theme_thumbnail0" id="form-room-theme-thumbnail-0" accept="image/*" class="form-control" onchange="handleFileChange('form-room-theme-thumbnail-0','form-room-chosen-thumbnail-0')" required>

            <br>
            <label for="form-room-text-0" class="file-label"><b>Theme Name 0</b></label>
            <input type="text" name="text0" id="form-room-text-0" class="form-control" placeholder="Enter Theme0 Name here" required>
        </div>
        @if (!config('app.unlimited_image_size'))
            <span class="col-sm-3 help-block">Image must be less than 4 MB and resolution less than 4096x4096 pixels.</span>
        @endif
    </div>

    <div class="form-group">
        <label for="form-room-theme-1" class="col-sm-3 control-label">Theme 1</label>
        <div class="col-sm-6">
            <label for="form-room-theme-1" class="file-label"><b>Upload Theme 1</b></label>
            <input type="hidden" name="chosen_theme1" id="form-room-chosen-theme-1" class="form-control">
            <input type="file" name="theme1" id="form-room-theme-1" accept="image/*" class="form-control" onchange="handleFileChange('form-room-theme-1','form-room-chosen-theme-1')">
            <br>

            <label for="form-room-theme-thumbnail-1" class="file-label"><b>Upload Thumbnail 1</b></label>
            <input type="hidden" id="form-room-chosen-thumbnail-1" class="form-control">
            <input type="file" name="theme_thumbnail1" id="form-room-theme-thumbnail-1" accept="image/*" class="form-control" onchange="handleFileChange('form-room-theme-thumbnail-1','form-room-chosen-thumbnail-1')">

            <br>
            <label for="form-room-text-1" class="file-label"><b>Theme Name 1</b></label>
            <input type="text" name="text1" id="form-room-text-1" class="form-control" placeholder="Enter Theme1 Name here">

            <br>
            <button type="button" class="btn btn-default btn-sm clear-btn-wrapper pull-right" id="clear-add-theme-1">Clear Theme 1</button>
        </div>
        @if (!config('app.unlimited_image_size'))
            <span class="col-sm-3 help-block">Image must be less than 4 MB and resolution less than 4096x4096 pixels.</span>
        @endif
    </div>

    <div class="form-group">
        <label for="form-room-theme-2" class="col-sm-3 control-label">Theme 2</label>
        <div class="col-sm-6">
            <label for="form-room-theme-2" class="file-label"><b>Upload Theme 2</b></label>
            <input type="hidden" name="chosen_theme2" id="form-room-chosen-theme-2" class="form-control">
            <input type="file" name="theme2" id="form-room-theme-2" accept="image/*" class="form-control" onchange="handleFileChange('form-room-theme-2','form-room-chosen-theme-2')">
            <br>

            <label for="form-room-theme-thumbnail-2" class="file-label"><b>Upload Thumbnail 2</b></label>
            <input type="hidden" id="form-room-chosen-thumbnail-2" class="form-control">
            <input type="file" name="theme_thumbnail2" id="form-room-theme-thumbnail-2" accept="image/*" class="form-control" onchange="handleFileChange('form-room-theme-thumbnail-2','form-room-chosen-thumbnail-2')">

            <br>
            <label for="form-room-text-2" class="file-label"><b>Theme Name 2</b></label>
            <input type="text" name="text2" id="form-room-text-2" class="form-control" placeholder="Enter Theme2 Name here">

            <br>
            <button type="button" class="btn btn-default btn-sm clear-btn-wrapper pull-right" id="clear-add-theme-2">Clear Theme 2</button>
        </div>
        @if (!config('app.unlimited_image_size'))
            <span class="col-sm-3 help-block">Image must be less than 4 MB and resolution less than 4096x4096 pixels.</span>
        @endif
    </div>

    <div class="form-group">
        <label for="form-room-theme-3" class="col-sm-3 control-label">Theme 3</label>
        <div class="col-sm-6">
            <label for="form-room-theme-3" class="file-label"><b>Upload Theme 3</b></label>
            <input type="hidden" name="chosen_theme3" id="form-room-chosen-theme-3" class="form-control">
            <input type="file" name="theme3" id="form-room-theme-3" accept="image/*" class="form-control" onchange="handleFileChange('form-room-theme-3','form-room-chosen-theme-3')">
            <br>

            <label for="form-room-theme-thumbnail-3" class="file-label">Upload Thumbnail 3</label>
            <input type="hidden" id="form-room-chosen-thumbnail-3" class="form-control">
            <input type="file" name="theme_thumbnail3" id="form-room-theme-thumbnail-3" accept="image/*" class="form-control" onchange="handleFileChange('form-room-theme-thumbnail-3','form-room-chosen-thumbnail-3')">
            <br>

            <label for="form-room-text-3" class="file-label"><b>Theme Name 3</b></label>
            <input type="text" name="text3" id="form-room-text-3" class="form-control" placeholder="Enter Theme3 Name here">

            <br>
            <button type="button" class="btn btn-default btn-sm clear-btn-wrapper pull-right" id="clear-add-theme-3">Clear Theme 3</button>
        </div>
        @if (!config('app.unlimited_image_size'))
            <span class="col-sm-3 help-block">Image must be less than 4 MB and resolution less than 4096x4096 pixels.</span>
        @endif
    </div>

    <div class="form-group">
        <label for="form-room-theme-4" class="col-sm-3 control-label">Theme 4</label>
        <div class="col-sm-6">
            <label for="form-room-theme-4" class="file-label"><b>Upload Theme 4</b></label>
            <input type="hidden" name="chosen_theme4" id="form-room-chosen-theme-4" class="form-control">
            <input type="file" name="theme4" id="form-room-theme-4" accept="image/*" class="form-control" onchange="handleFileChange('form-room-theme-4','form-room-chosen-theme-4')">
            <br>

            <label for="form-room-theme-thumbnail-4" class="file-label"><b>Upload Thumbnail 4</b></label>
            <input type="hidden" id="form-room-chosen-thumbnail-4" class="form-control">
            <input type="file" name="theme_thumbnail4" id="form-room-theme-thumbnail-4" accept="image/*" class="form-control" onchange="handleFileChange('form-room-theme-thumbnail-4','form-room-chosen-thumbnail-4')">

            <br>
            <label for="form-room-text-4" class="file-label"><b>Theme Name 4</b></label>
            <input type="text" name="text4" id="form-room-text-4" class="form-control" placeholder="Enter Theme4 Name here">

            <br>
            <button type="button" class="btn btn-default btn-sm clear-btn-wrapper pull-right" id="clear-add-theme-4">Clear Theme 4</button>
        </div>
        @if (!config('app.unlimited_image_size'))
            <span class="col-sm-3 help-block">Image must be less than 4 MB and resolution less than 4096x4096 pixels.</span>
        @endif
    </div>

    <div class="form-group">
        <label for="form-room-theme-5" class="col-sm-3 control-label">Theme 5</label>
        <div class="col-sm-6">
            <label for="form-room-theme-5" class="file-label"><b>Upload Theme 5</b></label>
            <input type="hidden" name="chosen_theme5" id="form-room-chosen-theme-5" class="form-control">
            <input type="file" name="theme5" id="form-room-theme-5" accept="image/*" class="form-control" onchange="handleFileChange('form-room-theme-5','form-room-chosen-theme-5')">
            <br>

            <label for="form-room-theme-thumbnail-5" class="file-label"><b>Upload Thumbnail 5</b></label>
            <input type="hidden" id="form-room-chosen-thumbnail-5" class="form-control">
            <input type="file" name="theme_thumbnail5" id="form-room-theme-thumbnail-5" accept="image/*" class="form-control" onchange="handleFileChange('form-room-theme-thumbnail-5','form-room-chosen-thumbnail-5')">
            <br>

            <label for="form-room-text-5" class="file-label"><b>Theme Name 5</b></label>
            <input type="text" name="text5" id="form-room-text-5" class="form-control" placeholder="Enter Theme5 Name here">

            <br>
            <button type="button" class="btn btn-default btn-sm clear-btn-wrapper pull-right pull-right" id="clear-add-theme-5">Clear Theme 5</button>
        </div>
        @if (!config('app.unlimited_image_size'))
            <span class="col-sm-3 help-block">Image must be less than 4 MB and resolution less than 4096x4096 pixels.</span>
        @endif
    </div>
@endif