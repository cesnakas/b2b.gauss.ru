<?php
///Запуск:
///require($_SERVER["DOCUMENT_ROOT"]."/local/migrations/201905311036_webforms.php");


use \Citfact\SiteCore\Tools\Migration\BaseMigration;

$migration = new BaseMigration;

$fieldsCallBackRu = [
    [
        'TITLE' => 'Имя',
        'ACTIVE' => 'Y',
        'SID' => 'NAME',
        'REQUIRED' => 'N',
        'C_SORT' => '100',
        'arANSWER' => [
            [
                'MESSAGE' => ' ',
                'ACTIVE' => 'Y',
                'FIELD_TYPE' => 'text',
            ]
        ],
    ],
    [
        'TITLE' => 'Телефон',
        'ACTIVE' => 'Y',
        'SID' => 'PHONE',
        'REQUIRED' => 'Y',
        'C_SORT' => '200',
        'arANSWER' => [
            [
                'MESSAGE' => ' ',
                'ACTIVE' => 'Y',
                'FIELD_TYPE' => 'text',
            ]
        ],
    ]
];

$fieldsContactToCompany = [
    [
        'TITLE' => 'Ваше имя',
        'ACTIVE' => 'Y',
        'SID' => 'NAME',
        'REQUIRED' => 'Y',
        'C_SORT' => '100',
        'arANSWER' => [
            [
                'MESSAGE' => ' ',
                'ACTIVE' => 'Y',
                'FIELD_TYPE' => 'text',
            ]
        ],
    ],
    [
        'TITLE' => 'Телефон',
        'ACTIVE' => 'Y',
        'SID' => 'PHONE',
        'REQUIRED' => 'Y',
        'C_SORT' => '200',
        'arANSWER' => [
            [
                'MESSAGE' => ' ',
                'ACTIVE' => 'Y',
                'FIELD_TYPE' => 'text',
            ]
        ],
    ],
    [
        'TITLE' => 'E-mail',
        'ACTIVE' => 'Y',
        'SID' => 'EMAIL',
        'REQUIRED' => 'Y',
        'C_SORT' => '300',
        'arANSWER' => [
            [
                'MESSAGE' => ' ',
                'ACTIVE' => 'Y',
                'FIELD_TYPE' => 'text',
            ]
        ],
    ],
    [
        'TITLE' => 'Город',
        'ACTIVE' => 'Y',
        'SID' => 'CITY',
        'REQUIRED' => 'N',
        'C_SORT' => '400',
        'arANSWER' => [
            [
                'MESSAGE' => ' ',
                'ACTIVE' => 'Y',
                'FIELD_TYPE' => 'text',
            ]
        ],
    ],
    [
        'TITLE' => 'Тема сообщения',
        'ACTIVE' => 'Y',
        'SID' => 'THEME',
        'REQUIRED' => 'Y',
        'C_SORT' => '500',
        'arANSWER' => [
            [
                'MESSAGE' => '- Выберите из списка -',
                'ACTIVE' => 'Y',
                'FIELD_TYPE' => 'dropdown',
            ],
            [
                'MESSAGE' => 'Гарантия',
                'ACTIVE' => 'Y',
                'FIELD_TYPE' => 'dropdown',
            ],
            [
                'MESSAGE' => 'Технические характеристики',
                'ACTIVE' => 'Y',
                'FIELD_TYPE' => 'dropdown',
            ],
            [
                'MESSAGE' => 'Сотрудничество',
                'ACTIVE' => 'Y',
                'FIELD_TYPE' => 'dropdown',
            ],
            [
                'MESSAGE' => 'Другое',
                'ACTIVE' => 'Y',
                'FIELD_TYPE' => 'dropdown',
            ],
        ],

    ],
    [
        'TITLE' => 'Текст сообщения',
        'ACTIVE' => 'Y',
        'SID' => 'COMMENT',
        'REQUIRED' => 'Y',
        'C_SORT' => '600',
        'arANSWER' => [
            [
                'MESSAGE' => ' ',
                'ACTIVE' => 'Y',
                'FIELD_TYPE' => 'textarea',
            ]
        ]
    ]
];

$fieldsCompanyProfile = [
	[
		'TITLE' => 'Если вы хотите изменить данные о компании, приложите подтверждающий документ:',
		'ACTIVE' => 'Y',
		'SID' => 'FILE',
		'REQUIRED' => 'Y',
		'C_SORT' => '100',
		'arANSWER' => [
			[
				'MESSAGE' => 'Если вы хотите изменить данные о компании приложите подтвержадющий документ:',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'file',
			]
		],
	]
];

$fieldsCustomizedSolutions = [
	[
		'TITLE' => 'ФИО',
		'ACTIVE' => 'Y',
		'SID' => 'NAME',
		'REQUIRED' => 'Y',
		'C_SORT' => '100',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'text',
			]
		],
	],
	[
		'TITLE' => 'E-mail',
		'ACTIVE' => 'Y',
		'SID' => 'EMAIL',
		'REQUIRED' => 'Y',
		'C_SORT' => '200',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'text',
			]
		],
	],
	[
		'TITLE' => 'Текст запроса',
		'ACTIVE' => 'Y',
		'SID' => 'COMMENT',
		'REQUIRED' => 'Y',
		'C_SORT' => '300',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'textarea',
			]
		]
	]
];

$fieldsFeedback = [
    [
        'TITLE' => 'Ваш телефон',
        'ACTIVE' => 'Y',
        'SID' => 'PHONE',
        'REQUIRED' => 'Y',
        'C_SORT' => '200',
        'arANSWER' => [
            [
                'MESSAGE' => ' ',
                'ACTIVE' => 'Y',
                'FIELD_TYPE' => 'text',
            ]
        ],
    ],

	[
		'TITLE' => 'Текст сообщения',
		'ACTIVE' => 'Y',
		'SID' => 'COMMENT',
		'REQUIRED' => 'N',
		'C_SORT' => '300',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'textarea',
			]
		]
	]
];

$fieldsEducation = [
	[
		'TITLE' => 'Название компании',
		'ACTIVE' => 'Y',
		'SID' => 'NAME',
		'REQUIRED' => 'Y',
		'C_SORT' => '100',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'text',
			]
		],
	],
	[
		'TITLE' => 'Ф.И.О. менеджера Gauss',
		'ACTIVE' => 'Y',
		'SID' => 'MANAGER_NAME',
		'REQUIRED' => 'Y',
		'C_SORT' => '200',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'text',
			]
		],
	],
	[
		'TITLE' => 'Кол-во сотрудников, которые будут участвовать в обучении',
		'ACTIVE' => 'Y',
		'SID' => 'COUNT_EMP',
		'REQUIRED' => 'Y',
		'C_SORT' => '300',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'text',
			]
		]
	],
	[
		'TITLE' => 'Род деятельности сотрудников',
		'ACTIVE' => 'Y',
		'SID' => 'TYPE_OF_STAFF',
		'REQUIRED' => 'Y',
		'C_SORT' => '400',
		'arANSWER' => [
			[
				'MESSAGE'=> '- Выберите из списка -',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'dropdown',
			],
			[
				'MESSAGE'=> 'Продавцы ТТ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'dropdown',
			],
			[
				'MESSAGE'=> 'Торговые представители',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'dropdown',
			],
			[
				'MESSAGE'=> 'Менеджеры b2b-продаж',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'dropdown',
			],
			[
				'MESSAGE'=> 'Другое',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'dropdown',
			],
		]
	],
	[
		'TITLE' => 'Формат обучения',
		'ACTIVE' => 'Y',
		'SID' => 'LEARNING_FORMAT',
		'REQUIRED' => 'Y',
		'C_SORT' => '500',
		'arANSWER' => [
            [
                'MESSAGE'=> '- Выберите из списка -',
                'ACTIVE' => 'Y',
                'FIELD_TYPE' => 'dropdown',
            ],
			[
				'MESSAGE'=> 'Вебинар',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'dropdown',
			],
			[
				'MESSAGE'=> 'Семинар',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'dropdown',
			],
			[
				'MESSAGE'=> 'Бизнес-тренинг',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'dropdown',
			],
		]
	],
	[
		'TITLE' => 'Начало обучения',
		'ACTIVE' => 'Y',
		'SID' => 'START_DATE',
		'REQUIRED' => 'Y',
		'C_SORT' => '600',
		'arANSWER' => [
			[
				'MESSAGE'=> ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'text',
			]
		]
	],
	[
		'TITLE' => 'Комментарий',
		'ACTIVE' => 'Y',
		'SID' => 'COMMENT',
		'REQUIRED' => 'N',
		'C_SORT' => '700',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'textarea',
			]
		]
	]
];

$fieldsPurchaseReturns = [
	[
		'TITLE' => 'Основание возврата товара (номер счета/накладной)',
		'ACTIVE' => 'Y',
		'SID' => 'NUMBER',
		'REQUIRED' => 'Y',
		'C_SORT' => '100',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'text',
			]
		],
	],
	[
		'TITLE' => 'Укажите причину возврата/обмена',
		'ACTIVE' => 'Y',
		'SID' => 'REASON',
		'REQUIRED' => 'Y',
		'C_SORT' => '200',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'textarea',
			]
		]
	],
	[
		'TITLE' => 'Комментарий',
		'ACTIVE' => 'Y',
		'SID' => 'COMMENT',
		'REQUIRED' => 'N',
		'C_SORT' => '300',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'textarea',
			]
		]
	],
	[
		'TITLE' => 'Выбрать файл',
		'ACTIVE' => 'Y',
		'SID' => 'FILE_1',
		'REQUIRED' => 'Y',
		'C_SORT' => '400',
		'arANSWER' => [
			[
				'MESSAGE' => 'К заявке вам необходимо прикрепить заполненное на возврат
            товара, а также фотографии товара.',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'file',
			]
		],
	],
	[
		'TITLE' => 'Выбрать файл',
		'ACTIVE' => 'Y',
		'SID' => 'FILE_2',
		'REQUIRED' => 'Y',
		'C_SORT' => '500',
		'arANSWER' => [
			[
				'MESSAGE' => 'К заявке вам необходимо прикрепить заполненное на возврат
            товара, а также фотографии товара.',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'file',
			]
		],
	],
	[
		'TITLE' => 'Выбрать файл',
		'ACTIVE' => 'Y',
		'SID' => 'FILE_3',
		'REQUIRED' => 'Y',
		'C_SORT' => '600',
		'arANSWER' => [
			[
				'MESSAGE' => 'К заявке вам необходимо прикрепить заполненное на возврат
            товара, а также фотографии товара.',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'file',
			]
		],
	],
	[
		'TITLE' => 'Выбрать файл',
		'ACTIVE' => 'Y',
		'SID' => 'FILE_4',
		'REQUIRED' => 'Y',
		'C_SORT' => '700',
		'arANSWER' => [
			[
				'MESSAGE' => 'К заявке вам необходимо прикрепить заполненное на возврат
            товара, а также фотографии товара.',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'file',
			]
		],
	],
	[
		'TITLE' => 'Выбрать файл',
		'ACTIVE' => 'Y',
		'SID' => 'FILE_5',
		'REQUIRED' => 'Y',
		'C_SORT' => '800',
		'arANSWER' => [
			[
				'MESSAGE' => 'К заявке вам необходимо прикрепить заполненное на возврат
            товара, а также фотографии товара.',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'file',
			]
		],
	],
	[
		'TITLE' => 'Выбрать файл',
		'ACTIVE' => 'Y',
		'SID' => 'FILE_6',
		'REQUIRED' => 'Y',
		'C_SORT' => '900',
		'arANSWER' => [
			[
				'MESSAGE' => 'К заявке вам необходимо прикрепить заполненное на возврат
            товара, а также фотографии товара.',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'file',
			]
		],
	],
	[
		'TITLE' => 'Выбрать файл',
		'ACTIVE' => 'Y',
		'SID' => 'FILE_7',
		'REQUIRED' => 'Y',
		'C_SORT' => '1000',
		'arANSWER' => [
			[
				'MESSAGE' => 'К заявке вам необходимо прикрепить заполненное на возврат
            товара, а также фотографии товара.',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'file',
			]
		],
	],
	[
		'TITLE' => 'Выбрать файл',
		'ACTIVE' => 'Y',
		'SID' => 'FILE_8',
		'REQUIRED' => 'Y',
		'C_SORT' => '1100',
		'arANSWER' => [
			[
				'MESSAGE' => 'К заявке вам необходимо прикрепить заполненное на возврат
            товара, а также фотографии товара.',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'file',
			]
		],
	],
	[
		'TITLE' => 'Выбрать файл',
		'ACTIVE' => 'Y',
		'SID' => 'FILE_9',
		'REQUIRED' => 'Y',
		'C_SORT' => '1200',
		'arANSWER' => [
			[
				'MESSAGE' => 'К заявке вам необходимо прикрепить заполненное на возврат
            товара, а также фотографии товара.',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'file',
			]
		],
	],
	[
		'TITLE' => 'Выбрать файл',
		'ACTIVE' => 'Y',
		'SID' => 'FILE_10',
		'REQUIRED' => 'Y',
		'C_SORT' => '1300',
		'arANSWER' => [
			[
				'MESSAGE' => 'К заявке вам необходимо прикрепить заполненное на возврат
            товара, а также фотографии товара.',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'file',
			]
		],
	],

];

$fieldsFeedbackReview = [
	[
		'TITLE' => 'Текст отзыва',
		'ACTIVE' => 'Y',
		'SID' => 'TEXT',
		'REQUIRED' => 'Y',
		'C_SORT' => '100',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'textarea',
			]
		]
	]
];

$fieldsFeedbackSuggestions = [
	[
		'TITLE' => 'Текст сообщения',
		'ACTIVE' => 'Y',
		'SID' => 'TEXT',
		'REQUIRED' => 'Y',
		'C_SORT' => '100',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'textarea',
			]
		]
	]
];

$fieldsFeedbackClaim = [
	[
		'TITLE' => 'Вид претензии',
		'ACTIVE' => 'Y',
		'SID' => 'TYPE',
		'REQUIRED' => 'Y',
		'C_SORT' => '100',
		'arANSWER' => [
			[
				'MESSAGE'=> 'По работе портала',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'radio',
			],
			[
				'MESSAGE'=> 'По товарам',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'radio',
			],
			[
				'MESSAGE'=> 'По работе менеджера',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'radio',
			],
		]
	],

	[
		'TITLE' => 'Текст сообщения',
		'ACTIVE' => 'Y',
		'SID' => 'TEXT',
		'REQUIRED' => 'Y',
		'C_SORT' => '200',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'textarea',
			]
		]
	],

	[
		'TITLE' => 'Прикрепить файл',
		'ACTIVE' => 'Y',
		'SID' => 'FILE_1',
		'REQUIRED' => 'N',
		'C_SORT' => '300',
		'arANSWER' => [
			[
				'MESSAGE' => 'К заявке вам необходимо прикрепить заполненное на возврат
            товара, а также фотографии товара.',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'file',
			]
		],
	],
	[
		'TITLE' => 'Прикрепить файл',
		'ACTIVE' => 'Y',
		'SID' => 'FILE_2',
		'REQUIRED' => 'N',
		'C_SORT' => '400',
		'arANSWER' => [
			[
				'MESSAGE' => 'К заявке вам необходимо прикрепить заполненное на возврат
            товара, а также фотографии товара.',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'file',
			]
		],
	],
	[
		'TITLE' => 'Прикрепить файл',
		'ACTIVE' => 'Y',
		'SID' => 'FILE_3',
		'REQUIRED' => 'N',
		'C_SORT' => '500',
		'arANSWER' => [
			[
				'MESSAGE' => 'К заявке вам необходимо прикрепить заполненное на возврат
            товара, а также фотографии товара.',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'file',
			]
		],
	],
	[
		'TITLE' => 'Прикрепить файл',
		'ACTIVE' => 'Y',
		'SID' => 'FILE_4',
		'REQUIRED' => 'N',
		'C_SORT' => '600',
		'arANSWER' => [
			[
				'MESSAGE' => 'К заявке вам необходимо прикрепить заполненное на возврат
            товара, а также фотографии товара.',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'file',
			]
		],
	],
	[
		'TITLE' => 'Прикрепить файл',
		'ACTIVE' => 'Y',
		'SID' => 'FILE_5',
		'REQUIRED' => 'N',
		'C_SORT' => '700',
		'arANSWER' => [
			[
				'MESSAGE' => 'К заявке вам необходимо прикрепить заполненное на возврат
            товара, а также фотографии товара.',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'file',
			]
		],
	],
	[
		'TITLE' => 'Прикрепить файл',
		'ACTIVE' => 'Y',
		'SID' => 'FILE_6',
		'REQUIRED' => 'N',
		'C_SORT' => '800',
		'arANSWER' => [
			[
				'MESSAGE' => 'К заявке вам необходимо прикрепить заполненное на возврат
            товара, а также фотографии товара.',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'file',
			]
		],
	],
	[
		'TITLE' => 'Прикрепить файл',
		'ACTIVE' => 'Y',
		'SID' => 'FILE_7',
		'REQUIRED' => 'N',
		'C_SORT' => '900',
		'arANSWER' => [
			[
				'MESSAGE' => 'К заявке вам необходимо прикрепить заполненное на возврат
            товара, а также фотографии товара.',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'file',
			]
		],
	],
	[
		'TITLE' => 'Прикрепить файл',
		'ACTIVE' => 'Y',
		'SID' => 'FILE_8',
		'REQUIRED' => 'N',
		'C_SORT' => '1000',
		'arANSWER' => [
			[
				'MESSAGE' => 'К заявке вам необходимо прикрепить заполненное на возврат
            товара, а также фотографии товара.',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'file',
			]
		],
	],
	[
		'TITLE' => 'Прикрепить файл',
		'ACTIVE' => 'Y',
		'SID' => 'FILE_9',
		'REQUIRED' => 'N',
		'C_SORT' => '1100',
		'arANSWER' => [
			[
				'MESSAGE' => 'К заявке вам необходимо прикрепить заполненное на возврат
            товара, а также фотографии товара.',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'file',
			]
		],
	],
	[
		'TITLE' => 'Прикрепить файл',
		'ACTIVE' => 'Y',
		'SID' => 'FILE_10',
		'REQUIRED' => 'N',
		'C_SORT' => '1200',
		'arANSWER' => [
			[
				'MESSAGE' => 'К заявке вам необходимо прикрепить заполненное на возврат
            товара, а также фотографии товара.',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'file',
			]
		],
	],
];

$fieldsDocumentsTechnicalDocumentation = [
	[
		'TITLE' => 'Текст сообщения',
		'ACTIVE' => 'Y',
		'SID' => 'TEXT',
		'REQUIRED' => 'Y',
		'C_SORT' => '100',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'textarea',
			]
		]
	]
];

$fieldsDocumentsInvoice = [
	[
		'TITLE' => 'Сумма платежа',
		'ACTIVE' => 'Y',
		'SID' => 'TEXT',
		'REQUIRED' => 'Y',
		'C_SORT' => '100',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'text',
			]
		],
	],
	[
		'TITLE' => 'Дата платежа',
		'ACTIVE' => 'Y',
		'SID' => 'DATE',
		'REQUIRED' => 'Y',
		'C_SORT' => '200',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'text',
			]
		],
	],
];

$fieldsDocumentsActOfReconciliation = [
	[
		'TITLE' => 'С',
		'ACTIVE' => 'Y',
		'SID' => 'DATE_FROM',
		'REQUIRED' => 'Y',
		'C_SORT' => '100',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'text',
			]
		],
	],
	[
		'TITLE' => 'По',
		'ACTIVE' => 'Y',
		'SID' => 'DATE_TO',
		'REQUIRED' => 'Y',
		'C_SORT' => '200',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'text',
			]
		],
	],
];

$fieldsDocumentsForwardingReceipt = [
	[
		'TITLE' => 'Номер заказа',
		'ACTIVE' => 'Y',
		'SID' => 'TEXT',
		'REQUIRED' => 'Y',
		'C_SORT' => '100',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'text',
			]
		],
	]
];


$fieldsPosEquip = [
	[
		'TITLE' => 'ФИО',
		'ACTIVE' => 'Y',
		'SID' => 'NAME',
		'REQUIRED' => 'Y',
		'C_SORT' => '100',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'text',
			]
		],
	],
	[
		'TITLE' => 'Телефон',
		'ACTIVE' => 'Y',
		'SID' => 'PHONE',
		'REQUIRED' => 'Y',
		'C_SORT' => '200',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'text',
			]
		],
	],
	[
		'TITLE' => 'Товары',
		'ACTIVE' => 'Y',
		'SID' => 'PRODUCTS',
		'REQUIRED' => 'Y',
		'C_SORT' => '300',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'textarea',
			]
		],
	],
];

$fieldsAskQuestion = [
	[
		'TITLE' => 'E-mail',
		'ACTIVE' => 'Y',
		'SID' => 'EMAIL',
		'REQUIRED' => 'Y',
		'C_SORT' => '200',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'hidden',
				'FIELD_HIDDEN_TYPE' => 'text',
			]
		],
	],
	[
		'TITLE' => 'Текст сообщения',
		'ACTIVE' => 'Y',
		'SID' => 'TEXT',
		'REQUIRED' => 'Y',
		'C_SORT' => '100',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'textarea',
			]
		]
	]
];

$fieldsSouvenirs = [
	[
		'TITLE' => 'ФИО',
		'ACTIVE' => 'Y',
		'SID' => 'NAME',
		'REQUIRED' => 'Y',
		'C_SORT' => '100',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'text',
			]
		],
	],
	[
		'TITLE' => 'Телефон',
		'ACTIVE' => 'Y',
		'SID' => 'PHONE',
		'REQUIRED' => 'Y',
		'C_SORT' => '200',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'text',
			]
		],
	],
	[
		'TITLE' => 'Товары',
		'ACTIVE' => 'Y',
		'SID' => 'PRODUCTS',
		'REQUIRED' => 'Y',
		'C_SORT' => '300',
		'arANSWER' => [
			[
				'MESSAGE' => ' ',
				'ACTIVE' => 'Y',
				'FIELD_TYPE' => 'textarea',
			]
		],
	],
];

$migration->setWebForm(
    [
        'NAME' => 'Заказать звонок',
        'arMENU' => ['ru' => 'Заказать звонок', 'en' => 'Заказать звонок'],
        'SID' => 'SIMPLE_FORM_7',
        'LID' => 'SIMPLE_FORM_7',
        'ID' => '7',
        'MAIL_TEMPLATES_COUNT' => 1,
        'arSITE' => ['s1'],
        'BUTTON' => 'Отправить',
        'STAT_EVENT1' => 'form',
        'STAT_EVENT2' => 'callback_form',
    ],
    $fieldsCallBackRu
);

$migration->setWebForm(
    [
        'NAME' => 'Обратиться в компанию',
        'arMENU' => ['ru' => 'Обратиться в компанию', 'en' => 'Обратиться в компанию'],
        'SID' => 'SIMPLE_FORM_8',
        'LID' => 'SIMPLE_FORM_8',
        'ID' => '8',
        'MAIL_TEMPLATES_COUNT' => 1,
        'arSITE' => ['s1'],
        'BUTTON' => 'Отправить сообщение',
        'STAT_EVENT1' => 'form',
        'STAT_EVENT2' => 'company_form',
    ],
    $fieldsContactToCompany
);

$migration->setWebForm(
    [
        'NAME' => 'Если у вас остались вопросы, задайте их нам',
        'arMENU' => ['ru' => 'Форма обратной связи', 'en' => 'Форма обратной связи'],
        'SID' => 'SIMPLE_FORM_6',
        'LID' => 'SIMPLE_FORM_6',
        'ID' => '6',
        'MAIL_TEMPLATES_COUNT' => 1,
        'arSITE' => ['s1'],
        'BUTTON' => 'Написать',
        'STAT_EVENT1' => 'form',
        'STAT_EVENT2' => 'feedback_form',
    ],
    $fieldsFeedback
);

$migration->setWebForm(
	[
		'NAME' => 'Изменить данные компании',
		'arMENU' => ['ru' => 'Изменить данные компании', 'en' => 'Изменить данные компании'],
		'SID' => 'SIMPLE_FORM_4',
		'LID' => 'SIMPLE_FORM_4',
		'ID' => '4',
		'MAIL_TEMPLATES_COUNT' => 1,
		'arSITE' => ['s1'],
		'BUTTON' => 'Отправить заявку',
		'STAT_EVENT1' => 'form',
		'STAT_EVENT2' => 'company_profile_form',
	],
	$fieldsCompanyProfile
);

$migration->setWebForm(
	[
		'NAME' => 'Заявка на индивидуальные решения',
		'arMENU' => ['ru' => 'Заявка на индивидуальные решения', 'en' => 'Заявка на индивидуальные решения'],
		'SID' => 'SIMPLE_FORM_5',
		'LID' => 'SIMPLE_FORM_5',
		'ID' => '5',
		'MAIL_TEMPLATES_COUNT' => 1,
		'arSITE' => ['s1'],
		'BUTTON' => 'Отправить заявку',
		'STAT_EVENT1' => 'form',
		'STAT_EVENT2' => 'customized_solutions_form',
	],
	$fieldsCustomizedSolutions
);

$migration->setWebForm(
	[
		'NAME' => 'Заявка на обучение',
		'arMENU' => ['ru' => 'Заявка на обучение', 'en' => 'Заявка на обучение'],
		'SID' => 'SIMPLE_FORM_9',
		'LID' => 'SIMPLE_FORM_9',
		'ID' => '9',
		'MAIL_TEMPLATES_COUNT' => 1,
		'arSITE' => ['s1'],
		'BUTTON' => 'Отправить заявку',
		'STAT_EVENT1' => 'form',
		'STAT_EVENT2' => 'education_form',
	],
	$fieldsEducation
);

$migration->setWebForm(
	[
		'NAME' => 'Заявка на возврат/обмен товара',
		'arMENU' => ['ru' => 'Заявка на возврат/обмен товара', 'en' => 'Заявка на возврат/обмен товара'],
		'SID' => 'SIMPLE_FORM_10',
		'LID' => 'SIMPLE_FORM_10',
		'ID' => '10',
		'MAIL_TEMPLATES_COUNT' => 1,
		'arSITE' => ['s1'],
		'BUTTON' => 'Отправить заявку',
		'STAT_EVENT1' => 'form',
		'STAT_EVENT2' => 'purchase_returns_form',
	],
	$fieldsPurchaseReturns
);

$migration->setWebForm(
	[
		'NAME' => 'Обратная связь - Отзыв',
		'arMENU' => ['ru' => 'Обратная связь - Отзыв', 'en' => 'Обратная связь - Отзыв'],
		'SID' => 'SIMPLE_FORM_11',
		'LID' => 'SIMPLE_FORM_11',
		'ID' => '11',
		'MAIL_TEMPLATES_COUNT' => 1,
		'arSITE' => ['s1'],
		'BUTTON' => 'Отправить',
		'STAT_EVENT1' => 'form',
		'STAT_EVENT2' => 'feedback_review_form',
	],
	$fieldsFeedbackReview
);

$migration->setWebForm(
	[
		'NAME' => 'Обратная связь - Предложения',
		'arMENU' => ['ru' => 'Обратная связь - Предложения', 'en' => 'Обратная связь - Предложения'],
		'SID' => 'SIMPLE_FORM_12',
		'LID' => 'SIMPLE_FORM_12',
		'ID' => '12',
		'MAIL_TEMPLATES_COUNT' => 1,
		'arSITE' => ['s1'],
		'BUTTON' => 'Отправить',
		'STAT_EVENT1' => 'form',
		'STAT_EVENT2' => 'feedback_suggestions_form',
	],
	$fieldsFeedbackSuggestions
);

$migration->setWebForm(
	[
		'NAME' => 'Обратная связь - Претензия',
		'arMENU' => ['ru' => 'Обратная связь - Претензия', 'en' => 'Обратная связь - Претензия'],
		'SID' => 'SIMPLE_FORM_13',
		'LID' => 'SIMPLE_FORM_13',
		'ID' => '13',
		'MAIL_TEMPLATES_COUNT' => 1,
		'arSITE' => ['s1'],
		'BUTTON' => 'Отправить',
		'STAT_EVENT1' => 'form',
		'STAT_EVENT2' => 'feedback_claim_form',
	],
	$fieldsFeedbackClaim
);

$migration->setWebForm(
	[
		'NAME' => 'Документы - Техническая документация',
		'arMENU' => ['ru' => 'Документы - Техническая документация', 'en' => 'Документы - Техническая документация'],
		'SID' => 'SIMPLE_FORM_14',
		'LID' => 'SIMPLE_FORM_14',
		'ID' => '14',
		'MAIL_TEMPLATES_COUNT' => 1,
		'arSITE' => ['s1'],
		'BUTTON' => 'Отправить заявку',
		'STAT_EVENT1' => 'form',
		'STAT_EVENT2' => 'documents_technical_documentation_form',
	],
	$fieldsDocumentsTechnicalDocumentation
);


$migration->setWebForm(
	[
		'NAME' => 'Документы - Счет-фактура на аванс',
		'arMENU' => ['ru' => 'Документы - Счет-фактура на аванс', 'en' => 'Документы - Счет-фактура на аванс'],
		'SID' => 'SIMPLE_FORM_15',
		'LID' => 'SIMPLE_FORM_15',
		'ID' => '15',
		'MAIL_TEMPLATES_COUNT' => 1,
		'arSITE' => ['s1'],
		'BUTTON' => 'Запросить счет-фактуру',
		'STAT_EVENT1' => 'form',
		'STAT_EVENT2' => 'documents_invoice_form',
	],
	$fieldsDocumentsInvoice
);

$migration->setWebForm(
	[
		'NAME' => 'Документы - Акт сверки',
		'arMENU' => ['ru' => 'Документы - Акт сверки', 'en' => 'Документы - Акт сверки'],
		'SID' => 'SIMPLE_FORM_16',
		'LID' => 'SIMPLE_FORM_16',
		'ID' => '16',
		'MAIL_TEMPLATES_COUNT' => 1,
		'arSITE' => ['s1'],
		'BUTTON' => 'Запросить документ',
		'STAT_EVENT1' => 'form',
		'STAT_EVENT2' => 'documents_act_of_reconciliation_form',
	],
	$fieldsDocumentsActOfReconciliation
);

$migration->setWebForm(
	[
		'NAME' => 'Документы - Экспедиторская расписка',
		'arMENU' => ['ru' => 'Документы - Экспедиторская расписка', 'en' => 'Документы - Экспедиторская расписка'],
		'SID' => 'SIMPLE_FORM_17',
		'LID' => 'SIMPLE_FORM_17',
		'ID' => '17',
		'MAIL_TEMPLATES_COUNT' => 1,
		'arSITE' => ['s1'],
		'BUTTON' => 'Запросить документ',
		'STAT_EVENT1' => 'form',
		'STAT_EVENT2' => 'documents_forwarding_receipt_form',
	],
	$fieldsDocumentsForwardingReceipt
);

$migration->setWebForm(
	[
		'NAME' => 'Заявка на торговое оборудование',
		'arMENU' => ['ru' => 'Заявка на торговое оборудование', 'en' => 'Заявка на торговое оборудование'],
		'SID' => 'SIMPLE_FORM_18',
		'LID' => 'SIMPLE_FORM_18',
		'ID' => '18',
		'MAIL_TEMPLATES_COUNT' => 1,
		'arSITE' => ['s1'],
		'BUTTON' => 'Отправить заявку',
		'STAT_EVENT1' => 'form',
		'STAT_EVENT2' => 'pos_equip_form',
	],
	$fieldsPosEquip
);

$migration->setWebForm(
	[
		'NAME' => 'Задать вопрос ассистенту',
		'arMENU' => ['ru' => 'Задать вопрос', 'en' => 'Задать вопрос'],
		'SID' => 'SIMPLE_FORM_19',
		'LID' => 'SIMPLE_FORM_19',
		'ID' => '19',
		'MAIL_TEMPLATES_COUNT' => 1,
		'arSITE' => ['s1'],
		'BUTTON' => 'Отправить',
		'STAT_EVENT1' => 'form',
		'STAT_EVENT2' => 'ask_question_form',
	],
	$fieldsAskQuestion
);

$migration->setWebForm(
	[
		'NAME' => 'Заявка на сувенирную продукцию',
		'arMENU' => ['ru' => 'Заявка на сувенирную продукцию', 'en' => 'Заявка на сувенирную продукцию'],
		'SID' => 'SIMPLE_FORM_20',
		'LID' => 'SIMPLE_FORM_20',
		'ID' => '20',
		'MAIL_TEMPLATES_COUNT' => 1,
		'arSITE' => ['s1'],
		'BUTTON' => 'Отправить заявку',
		'STAT_EVENT1' => 'form',
		'STAT_EVENT2' => 'souvenirs_form',
	],
	$fieldsSouvenirs
);