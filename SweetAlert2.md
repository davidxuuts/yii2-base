# yii2-sweetalert2-widget

[![Latest Version](https://poser.pugx.org/davidxu/yii2-sweetalert2-widget/v/stable)](https://packagist.org/packages/davidxu/yii2-sweetalert2-widget)
[![Software License](https://poser.pugx.org/davidxu/yii2-sweetalert2-widget/license)](https://github.com/davidxu/yii2-sweetalert2-widget/blob/master/LICENSE.md)
[![Total Downloads](https://poser.pugx.org/davidxu/yii2-sweetalert2-widget/downloads)](https://packagist.org/packages/davidxu/yii2-sweetalert2-widget)

Renders a [SweetAlert2](https://sweetalert2.github.io/) widget for Yii2.

![Logo SweetAlert2](https://sweetalert2.github.io/images/SweetAlert2.png)

## Usage
Simply use it in your code by:

## Flash message

View:
```
<?= \davidxu\base\SweetAlert2::widget() ?>
```

Controller:
```
<?php
Yii::$app->session->setFlash('', [
    [
        'title' => 'Auto close alert!',
        'text' => 'I will close in 2 seconds.',
        'timer' => 2000,
    ],
    [
        'callback' => new \yii\web\JsExpression("
            function (result) {
                // handle dismiss, result.dismiss can be 'cancel', 'overlay', 'close', and 'timer'
                if (result.isConfirmed) {
                    return true;
                }
            }
        "),
    ],
]);
```

## Render Widget
View:
```
<?php
use davidxu\base\SweetAlert2;
```

A message with auto close timer
```
<?= SweetAlert2::widget([
    'options' => [
        'title' => 'Auto close alert!',
        'text' => 'I will close in 2 seconds.',
        'timer' => 2000,
    ],
    'callback' => new \yii\web\JsExpression("
        function (result) {
            if (result.dismiss === 'timer') {
                console.log('I was closed by the timer')
            }
        }
    "),
]) ?>
```

Custom HTML description and buttons
```
<?= SweetAlert2::widget([
    'options' => [
        'title' => '<i>HTML</i> <u>example</u>',
        'icon' => 'success,
        'html' => 'You can use <b>bold text</b>,'
            . '<a href="//github.com">links</a> '
            . 'and other HTML tags',
        'showCloseButton' => true,
        'showCancelButton' => true,
        'confirmButtonText' => '<i class="fa fa-thumbs-up"></i> Great!',
        'cancelButtonText' => '<i class="fa fa-thumbs-down"></i>',
    ],
]) ?>
```

A warning message, with a function attached to the "Confirm"-button...
```
<?= SweetAlert2::widget([
    'options' => [
        'title' => 'Are you sure?',
        'text' => "You won't be able to revert this!",
        'icon' =>'warning',
        'showCancelButton' => true,
        'confirmButtonColor' => '#3085d6',
        'cancelButtonColor' => '#d33',
        'confirmButtonText' => 'Yes, delete it!',
    ],
    'callback' => new \yii\web\JsExpression("
        function (result) {
            if(result.value === true){
                swal.fire('Deleted!','Your file has been deleted.','success')
            }
        }
    "),
]) ?>
```

... and by passing a parameter, you can execute something else for "Cancel".
```
<?= SweetAlert2::widget([
    'options' => [
        'title' => 'Are you sure?',
        'text' => "You won't be able to revert this!",
        'icon' =>'warning',
        'showCancelButton' => true,
        'confirmButtonColor' => '#3085d6',
        'cancelButtonColor' => '#d33',
        'confirmButtonText' => 'Yes, delete it!',
        'cancelButtonText' => 'No, cancel!',
        'confirmButtonClass' => 'btn btn-success',
        'cancelButtonClass' => 'btn btn-danger',
        'buttonsStyling' => false,
    ],
    'callback' => new \yii\web\JsExpression("
        function (result) {
            if(result.value) {
                Swal.fire('Deleted!','Your file has been deleted.','success')
            }            
            if (result.dismiss === 'cancel') {
                Swal.fire(
                    'Cancelled',
                    'Your imaginary file is safe :)',
                    'error'
                )
            }
        }
    "),
]) ?>
```

## Input Types Example

Text:
```
<?= SweetAlert2::widget([
    'options' => [
        'title' => 'Input something',
        'input' => 'text',
        'showCancelButton' => true,
        'inputValidator' => new \yii\web\JsExpression("
            function (value) {
                return new Promise(function (resolve) {
                    if (value) {
                        resolve()
                    } else {
                        resolve('You need to write something!')
                    }
                })
            }
        ")
    ],
    'callback' => new \yii\web\JsExpression("
        function (result) {
            if(result.value) {
                Swal.fire({
                    type: 'success',
                    html: 'You entered: ' + result.value
                })
            }
        }
    "),
]) ?>
```

Email:
```
<?= SweetAlert2::widget([
    'options' => [
        'title' => 'Input email address',
        'input' => 'email',
    ],
    'callback' => new \yii\web\JsExpression("
        function (result) {
            Swal.fire({
                type: 'success',
                html: 'Entered email: ' + result.value
            })
        }
    "),
]) ?>
```

Password:
```
<?= SweetAlert2::widget([
    'options' => [
        'title' => 'Enter your password',
        'input' => 'password',
        'inputAttributes' => [
            'maxlength' => 10,
            'autocapitalize' => 'off',
            'autocorrect' => 'off',
        ]
    ],
    'callback' => new \yii\web\JsExpression("
        function (result) {
          if (result.value) {
              Swal.fire({
                  type: 'success',
                  html: 'Entered password: ' + result.value
              })
          }
        }
   "),
]) ?>
```

Textarea:
```
<?= SweetAlert2::widget([
    'options' => [
        'input' => 'textarea',
        'showCancelButton' => true,
    ],
    'callback' => new \yii\web\JsExpression("
        function (result) {
            if (result.value) {
                Swal.fire(result.value)
            }
        }
    "),
]) ?>
```

Select:
```
<?= SweetAlert2::widget([
    'options' => [
        'title' => 'Select Country',
        'input' => 'select',
        'inputOptions' => [
            'CHN' => 'China',
            'RUS' => 'Russia',
            'USA' => 'America',
        ],
        'inputPlaceholder' => 'Select country',
        'showCancelButton' => true,
        'inputValidator' => new \yii\web\JsExpression("
            function (value) {
                return new Promise(function (resolve) {
                    if (value === 'CHN') {
                        resolve()
                    } else {
                        resolve('You need to select CHN :)')
                    }
                })
            }
        ")
    ],
    'callback' => new \yii\web\JsExpression("
        function (result) {
            if (result.value) {
                Swal.fire({
                    type: 'success',
                    html: 'You selected: ' + result.value
                })
            }
        }
    "),
]) ?>
```

Radio:
```
<?php
$script = new \yii\web\JsExpression("
    // inputOptions can be an object or Promise
    var inputOptions = new Promise(function (resolve) {
        setTimeout(function () {
            resolve({
                '#ff0000': 'Red',
                '#00ff00': 'Green',
                '#0000ff': 'Blue'
            })
        }, 2000)
    })
");
$this->registerJs($script, \yii\web\View::POS_HEAD);

echo SweetAlert2::widget([
    'options' => [
        'title' => 'Select color',
        'input' => 'radio',
        'inputOptions' => new \yii\web\JsExpression("inputOptions"),
        'inputValidator' => new \yii\web\JsExpression("
            function (result) {
                return new Promise(function (resolve) {
                    if (result) {
                        resolve()
                    } else {
                        resolve('You need to select something!')
                    }
                })
            }
        ")
    ],
    'callback' => new \yii\web\JsExpression("
        function (result) {
            Swal.fire({
                type: 'success',
                html: 'You selected: ' + result.value
            })
        }
    "),
]); ?>
```

Checkbox:
```
<?= SweetAlert2::widget([
    'options' => [
        'title' => 'Terms and conditions',
        'input' => 'checkbox',
        'inputValue' => 1,
        'inputPlaceholder' => 'I agree with the terms and conditions',
        'confirmButtonText' => 'Continue <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>',
        'inputValidator' => new \yii\web\JsExpression("
            function (result) {
                return new Promise(function (resolve) {
                    if (result) {
                        resolve()
                    } else {
                        resolve('You need to agree with T&C')
                    }
                })
            }
        ")
    ],
    'callback' => new \yii\web\JsExpression("
        function (result) {
            Swal.fire({
                type: 'success',
                html: 'You agreed with T&C :' + result.value
            })
        }
    "),
]) ?>
```

File:
```
<?= SweetAlert2::widget([
    'options' => [
        'title' => 'Select image',
        'input' => 'file',
        'inputAttributes' => [
            'accept' => 'image/*',
            'aria-label' => 'Upload your profile picture',
        ],
    ],
    'callback' => new \yii\web\JsExpression("
        function(result) {
            var reader = new FileReader
            reader.onload = function (e) {
                Swal.fire({
                    title: 'Your uploaded picture',
                    imageUrl: e.target.result,
                    imageAlt: 'The uploaded picture'
                })
            }
            reader.readAsDataURL(result.value)
        }
    "),
]) ?>
```

Range:
```
<?= SweetAlert2::widget([
    'options' => [
        'title' => 'How old are you?',
        'icon' => 'question',
        'input' => 'range',
        'inputAttributes' => [
            'min' => 8,
            'max' => 120,
            'step' => 1,
        ],
        'inputValue' => 25,
    ]
]) ?>
```

Multiple inputs aren't supported, you can achieve them by using `html` and `preConfirm` parameters.
Inside the `preConfirm()` function you can pass the custom result to the `resolve()` function as a parameter:
```
<?= SweetAlert2::widget([
    'options' => [
        'title' => 'Multiple inputs',
        'html' => '<input id="swal-input1" class="swal2-input"> <input id="swal-input2" class="swal2-input">',
        'preConfirm' => new \yii\web\JsExpression("
            function () {
                return new Promise(function (resolve) {
                    resolve([
                        $('#swal-input1').val(),
                        $('#swal-input2').val()
                    ])
                })
            }
        "),
        'onOpen' => new \yii\web\JsExpression("
            function () {
                $('#swal-input1').focus()
            }
        "),
    ],
    'callback' => new \yii\web\JsExpression("
        function (result) {
            Swal.fire(JSON.stringify(result.value))
        }
    "),
]) ?>
```
Ajax request example
```
<?= SweetAlert2::widget([
    'options' => [
        'title' => 'Submit email to run ajax request',
        'input' => 'email',
        'showCancelButton' => true,
        'confirmButtonText' => 'Submit',
        'showLoaderOnConfirm' => true,
        'preConfirm' => new \yii\web\JsExpression("
            function (email) {
                return new Promise(function (resolve) {
                    setTimeout(function () {
                        if (email === 'taken@example.com') {
                            swal.showValidationError(
                                'This email is already taken.'
                            )
                        }
                        resolve()
                    }, 2000)
                })
            }
        "),
        'allowOutsideClick' => false,
    ],
    'callback' => new \yii\web\JsExpression("
        function (result) {
            if (result.value) {
                Swal.fire({
                    type: 'success',
                    title: 'Ajax request finished!',
                    html: 'Submitted email: ' + result.value
                })
            }
        }
    "),
]) ?>
```

## More Information
Please, check the [SweetAlert2](https://sweetalert2.github.io/)