<?php

return [
	'mode'                 => 'utf-8',
	'format'               => 'A4',
	'author'               => 'Your Company Name', // คอมเมนต์: เปลี่ยนเป็นชื่อบริษัทของคุณ
	'subject'              => '',
	'keywords'             => '',
	'creator'              => 'Laravel mPDF',
	'display_mode'         => 'fullpage',

    /**
     * คอมเมนต์: จุดสำคัญคือตรงนี้
     * เรากำหนดให้ mPDF ใช้โฟลเดอร์ storage/app/temp_pdf เป็นที่เก็บไฟล์ชั่วคราว
     * ซึ่งเป็นที่ที่ Laravel สามารถเขียนไฟล์ได้
     */
	'tempDir'              => storage_path('app/temp_pdf'),

    // คอมเมนต์: แก้ไข path ให้ตรงกับที่เก็บฟอนต์จริงของคุณ
    'font_path' => storage_path('fonts/'),
    'font_data' => [
        'thsarabunnew' => [ // ชื่อฟอนต์ที่ใช้ใน CSS
            'R'  => 'THSarabunNew.ttf',    // regular
            'B'  => 'THSarabunNew Bold.ttf',       // bold
            'I'  => 'THSarabunNew Italic.ttf',     // italic
            'BI' => 'THSarabunNew BoldItalic.ttf' // bold-italic
        ],
        // คอมเมนต์: เพิ่มฟอนต์ Sarabun มาตรฐานจาก Google Fonts เป็นอีกทางเลือก
        'sarabun' => [
            'R'  => 'Sarabun-Regular.ttf',
            'B'  => 'Sarabun-Bold.ttf',
            'I'  => 'Sarabun-Italic.ttf',
            'BI' => 'Sarabun-BoldItalic.ttf',
        ],
    ]
];