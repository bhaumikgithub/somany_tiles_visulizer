<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RoomModel extends Model {

    public function getIconAttribute($value)
    {
        if ($value) {
            return Storage::url($value);
        } else {
            return Storage::url('rooms/noiconfile.png');
        }
    }

    public function getImageAttribute($value) {
        if ($value) {
            return Storage::url($value);
        }
    }

    public function getShadowAttribute($value) {
        if ($value) {
            return Storage::url($value);
        }
    }

    public function getShadowMattAttribute($value) {
        if ($value) {
            return Storage::url($value);
        }
    }

    public function getTheme0Attribute($value) {
        if ($value) {
            return Storage::url($value);
        }
    }

    public function getTheme1Attribute($value) {
        if ($value) {
            return Storage::url($value);
        }
    }

    public function getTheme2Attribute($value) {
        if ($value) {
            return Storage::url($value);
        }
    }

    public function getTheme3Attribute($value) {
        if ($value) {
            return Storage::url($value);
        }
    }

    public function getTheme4Attribute($value) {
        if ($value) {
            return Storage::url($value);
        }
    }

    public function getTheme5Attribute($value) {
        if ($value) {
            return Storage::url($value);
        }
    }

    public function getThemeThumbnail0Attribute($value) {
        if ($value) {
            return Storage::url($value);
        }
    }

    public function getThemeThumbnail1Attribute($value) {
        if ($value) {
            return Storage::url($value);
        }
    }

    public function getThemeThumbnail2Attribute($value) {
        if ($value) {
            return Storage::url($value);
        }
    }

    public function getThemeThumbnail3Attribute($value) {
        if ($value) {
            return Storage::url($value);
        }
    }

    public function getThemeThumbnail4Attribute($value) {
        if ($value) {
            return Storage::url($value);
        }
    }

    public function getThemeThumbnail5Attribute($value) {
        if ($value) {
            return Storage::url($value);
        }
    }

}
