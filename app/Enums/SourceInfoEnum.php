<?php

namespace App\Enums;

enum SourceInfoEnum: string
{
    case SOCIAL_MEDIA  = 'Social Media';
    case WEBSITE_RESMI = 'Website resmi';
    case IKLAN         = 'Iklan';
    case POSTER        = 'Poster';
    case TEMAN         = 'Teman';
    case DOSEN         = 'Dosen';
}
