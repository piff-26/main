<?php

namespace App\Enums;

enum SourceInfoEnum: string
{
    case SOCIAL_MEDIA  = 'Social Media';
    case WEBSITE_RESMI = 'Website';
    case IKLAN         = 'Advertisement';
    case POSTER        = 'Poster';
    case TEMAN         = 'Friends';
    case DOSEN         = 'Lecturer';
}
