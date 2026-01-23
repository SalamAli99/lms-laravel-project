<?php

namespace App\Media;

use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CoursePathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        return "courses/course-{$media->model_id}/{$media->collection_name}/";
    }

    public function getPathForConversions(Media $media): string
    {
        return "courses/course-{$media->model_id}/{$media->collection_name}/conversions/";
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return "courses/course-{$media->model_id}/{$media->collection_name}/responsive/";
    }
}
