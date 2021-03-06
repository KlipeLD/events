@extends('layout')

@section('head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.5/css/bulma.css" rel="stylesheet"/>
    <link rel="stylesheet" href="./bootstrap.min.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    
    <script src="/dist/scheme-designer.min.js?23"></script>
     <!-- data for scheme -->
    <script type="text/javascript">
    var schemeData = [];
    var place;    
    var avail = new Boolean(false);
    @forelse($places as $i => $place)
    @if($places[$i]['is_available'])
        avail = true;
        place = {"ID":"place_{{$places[$i]['id']}}_1_1","NomBilKn":"994","ObjectName":"Place","ObjectType":"Place","Width":"{{$places[$i]['width']}}","Height":{{$places[$i]['height']}},"CX":{{$places[$i]['x']}},"CX2":{{$places[$i]['x']+$places[$i]['width']}},"CY":{{$places[$i]['y']}},"CY2":{{$places[$i]['y']+$places[$i]['height']}},"Angle":"0.00","Row":"1","Seat":{{$places[$i]['id']}},"cod_sec":"1","Name_sec":"1","FreeOfferSeat":"0","FontColor":"","FontSize":"0","Label":"1","MinX":"168","MinY":"88","MaxX":"952","MaxY":"712","BackColor":"FFCC66","avail":avail};
    @else
        avail = false;
        place = {"ID":"place_{{$places[$i]['id']}}_1_1","NomBilKn":"994","ObjectName":"Place","ObjectType":"Place","Width":"{{$places[$i]['width']}}","Height":{{$places[$i]['height']}},"CX":{{$places[$i]['x']}},"CX2":{{$places[$i]['x']+$places[$i]['width']}},"CY":{{$places[$i]['y']}},"CY2":{{$places[$i]['y']+$places[$i]['height']}},"Angle":"0.00","Row":"1","Seat":{{$places[$i]['id']}},"cod_sec":"1","Name_sec":"1","FreeOfferSeat":"0","FontColor":"","FontSize":"0","Label":"1","MinX":"168","MinY":"88","MaxX":"952","MaxY":"712","BackColor":"60666b","avail":avail};
    @endif
    
    schemeData.push(place);
    @empty
        
    @endforelse
    </script>
    
    <style>
        .canvas-holder {
            width: 100%;
            height: 500px;
            position:relative;
        }
        @media (max-width: 767px) {
            .canvas1_map_container {
                display: none;
            }
        }
    </style>
@endsection

@section('content')
    <div id="wrapper">
        <div id="page" class="container">
            <H1 class="heading has-text-weight-bold is-size-4">{{$partEvent['showName']}} - Событие № {{$partEvent['id']}} - Дата события: {{$partEvent['date']}}- Бронирование мест</H1>
            <form name="person" method="post" action="/parts/{{$partEvent['id']}}/reserve">
             @csrf   
                <div class="field">
                    <label class="label" for="name">Введите Ваше имя</label>
                </div>
                <div class="control">
                    <input class="input @error('name') is-danger @enderror "
                           type="text" name="name"  id="name" value="{{old('name')}}">
                    @error('name')
                        <p class="help is-danger">{{$errors->first('name')}}</p>
                    @enderror
                </div>
                <div class="field">
                    <label class="label" for="place">Выберите место</label>
                </div>
                <input style="display:none;" class="input"
                           type="text" name="places[]" id="places">
                <div class="container" style="max-width: 800px;">
                    <div style="text-align: center;">
                        <div style="position: relative;">
                            <div class="canvas-holder" style="">
                                <canvas id="canvas1" style="border: 1px solid #ccc;">
                                    Ваш браузер не поддерживает элемент canvas.
                                </canvas>
                            </div>
                        </div>
                        <div class="well">
                            <div class="row" >
                                <div class="col-sm-2" style="margin-bottom: 5px;">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="schemeDesigner.getZoomManager().zoomToCenter(10)">+</button>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="schemeDesigner.getZoomManager().zoomToCenter(-10)">-</button>
                                </div>
                                <div class="col-sm-2" style="margin-bottom: 5px;">
                                    <button type="button" class="btn btn-warning btn-sm" onclick="schemeDesigner.getStorageManager().showNodesParts()">
                                        Show grid
                                    </button>
                                </div>

                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-info btn-sm" onclick="schemeDesigner.getScrollManager().toCenter(); schemeDesigner.requestRenderAll();">
                                        Scroll to center
                                    </button>
                                </div>

                                <div class="col-sm-2" style="margin-bottom: 5px;">
                                    <button type="button" class="btn btn-warning btn-sm" onclick="schemeDesigner.getStorageManager().setLayerVisibility('background', !schemeDesigner.getStorageManager().getLayerById('background').isVisible())">
                                        Show/hide background
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    let nnn = [];
                    var backgroundLayer = new SchemeDesigner.Layer('background', {zIndex: 0, visible: false, active: false});
                    var defaultLayer = new SchemeDesigner.Layer('default', {zIndex: 10});

                    /**
                     * Render place function
                     * @param {SchemeObject} schemeObject
                     * @param {Scheme} schemeDesigner
                     * @param {View} view
                     */
                    var renderPlace = function (schemeObject, schemeDesigner, view) {
                        var context = view.getContext();

                        var objectParams = schemeObject.getParams();

                        var backgroundColor = '#' + objectParams.backgroundColor;

                        context.beginPath();
                        context.lineWidth = 4;
                        context.strokeStyle = 'white';

                        var isHovered = schemeObject.isHovered && !SchemeDesigner.Tools.touchSupported();

                        context.fillStyle = backgroundColor;

                        if (objectParams.isSelected && isHovered) {
                            context.strokeStyle = backgroundColor;
                        } else if (isHovered) {
                            context.fillStyle = 'white';
                            context.strokeStyle = backgroundColor;
                        } else if (objectParams.isSelected) {
                            context.strokeStyle = backgroundColor;
                        }

                        var relativeX = schemeObject.x;
                        var relativeY = schemeObject.y;

                        var width = schemeObject.getWidth();
                        var height = schemeObject.getHeight();
                        if (!isHovered && !objectParams.isSelected) {
                            var borderOffset = 4;
                            relativeX = relativeX + borderOffset;
                            relativeY = relativeY + borderOffset;
                            width = width - (borderOffset * 2);
                            height = height - (borderOffset * 2);
                        }

                        var halfWidth = width / 2;
                        var halfHeight = height / 2;

                        var circleCenterX = relativeX + halfWidth;
                        var circleCenterY = relativeY + halfHeight;

                        if (schemeObject.getRotation()) {
                            context.save();
                            context.translate( relativeX + halfWidth, relativeY + halfHeight);
                            context.rotate(schemeObject.getRotation() * Math.PI / 180);
                            context.rect(-halfWidth, -halfHeight, width, height);
                        } else {
                            context.arc(circleCenterX, circleCenterY, halfWidth, 0, Math.PI * 2);
                        }


                        context.fill();
                        context.stroke();

                        context.font = (Math.floor((schemeObject.getWidth() + schemeObject.getHeight()) / 4)) + 'px Arial';

                        if (objectParams.isSelected && isHovered) {
                            context.fillStyle = 'white';
                        } else if (isHovered) {
                            context.fillStyle = backgroundColor;
                        } else if (objectParams.isSelected) {
                            context.fillStyle = 'white';
                        }

                        if (objectParams.isSelected || isHovered) {
                            context.textAlign = 'center';
                            context.textBaseline = 'middle';
                            if (schemeObject.rotation) {
                                context.fillText(objectParams.seat,
                                    -(schemeObject.getWidth() / 2) + (schemeObject.getWidth() / 2),
                                    -(schemeObject.getHeight() / 2)  + (schemeObject.getHeight() / 2)
                                );
                            } else {
                                context.fillText(objectParams.seat, relativeX + (schemeObject.getWidth() / 2), relativeY + (schemeObject.getHeight() / 2));
                            }
                        }

                        if (schemeObject.rotation) {
                            context.restore();
                        }
                    };

                    /**
                     * Render label function
                     * @param {SchemeObject} schemeObject
                     * @param {Scheme} schemeDesigner
                     * @param {View} view
                     */

                    var renderLabel = function(schemeObject, schemeDesigner, view) {
                        var objectParams = schemeObject.getParams();
                        var fontSize = (objectParams.fontSize >> 0) * (96 / 72) * 3;

                        var context = view.getContext();

                        context.fillStyle = '#' + objectParams.fontColor;
                        context.font = fontSize + 'px Arial';
                        context.textAlign = 'center';
                        context.textBaseline = 'middle';
                        context.fillText(objectParams.sectorName, schemeObject.getX(), schemeObject.getY());
                    };

                    
                    var clickOnPlace = function (schemeObject, schemeDesigner, view, e)
                    {
                        var objectParams = schemeObject.getParams();
                        var index = nnn.indexOf(schemeObject.getId());
                        if(schemeObject.isActive())
                        {
                            if (index > -1) 
                            {
                                nnn.splice(index, 1);
                            }
                            else
                            {
                                nnn.push(schemeObject.getId());
                            }
                            objectParams.isSelected = !objectParams.isSelected;
                        }
                       document.getElementById('places').value = nnn;     
                    };

                    /**
                     * Creating places
                     */
                    for (var i = 0; i < schemeData.length; i++)
                    {
                        var objectData = schemeData[i];
                        var leftOffset = objectData.CX >> 0;
                        var topOffset = objectData.CY >> 0;
                        var width = objectData.Width >> 0;
                        var height = objectData.Height >> 0;
                        var rotation = objectData.Angle >> 0;

                        var schemeObject = new SchemeDesigner.SchemeObject({
                            /**
                             * Required params
                             */
                            x: 0.5 + leftOffset,
                            y: 0.5 + topOffset,
                            width: width,
                            height: height,
                            active: objectData.ObjectType == 'Place'? true : false,
                            renderFunction: objectData.ObjectType == 'Place' ? renderPlace : renderLabel,
                            cursorStyle: objectData.ObjectType == 'Place' ? 'pointer' : 'default',

                            /**
                             * Custom params (any names and count)
                             */
                            rotation: rotation,
                            id: 'place_' + i,
                            price: objectData.Price,
                            seat: objectData.Seat,
                            row: objectData.Row,
                            sectorName: objectData.Name_sec,
                            fontSize: objectData.FontSize,
                            backgroundColor: objectData.BackColor,
                            fontColor: objectData.FontColor,

                            isSelected: false,
                            clickFunction: clickOnPlace,
                            clearFunction: function (schemeObject, schemeDesigner, view) {
                                var context = view.getContext();

                                var borderWidth = 5;
                                context.clearRect(schemeObject.x - borderWidth,
                                    schemeObject.y - borderWidth,
                                    this.width + (borderWidth * 2),
                                    this.height + (borderWidth * 2)
                                );
                            }
                        });

                        defaultLayer.addObject(schemeObject);
                    }

                    /**
                     * add background object
                     */
                    backgroundLayer.addObject(new SchemeDesigner.SchemeObject({
                        x: 0.5,
                        y: 0.5,
                        width: 8600,
                        height: 7000,
                        cursorStyle: 'default',
                        renderFunction: function (schemeObject, schemeDesigner, view) {
                            var context = view.getContext();
                            context.beginPath();
                            context.lineWidth = 4;
                            context.strokeStyle = 'rgba(12, 200, 15, 0.2)';

                            context.fillStyle = 'rgba(12, 200, 15, 0.2)';


                            var width = schemeObject.width;
                            var height = schemeObject.height;

                            context.rect(schemeObject.x, schemeObject.y, width, height);


                            context.fill();
                            context.stroke();
                        }
                    }));

                    var canvas = document.getElementById('canvas1');
                    var schemeDesigner = new SchemeDesigner.Scheme(canvas, {
                        options: {
                            cacheSchemeRatio: 2
                        },
                        scroll: {
                            maxHiddenPart: 0.5
                        },
                        zoom: {
                            padding: 0.1,
                            maxScale: 8,
                            zoomCoefficient: 1.04
                        },
                        storage: {
                            treeDepth: 6
                        }
                    });

                    /**
                     * Adding layers with objects
                     */
                    schemeDesigner.addLayer(defaultLayer);
                    schemeDesigner.addLayer(backgroundLayer);

                    /**
                     * Show scheme
                     */
                    schemeDesigner.render();



                    canvas.addEventListener('schemeDesigner.beforeRenderAll', function (e) {
                        console.time('renderAll');
                    }, false);

                    canvas.addEventListener('schemeDesigner.afterRenderAll', function (e) {
                        console.timeEnd('renderAll');
                    }, false);

                    canvas.addEventListener('schemeDesigner.clickOnObject', function (e) {
                        console.log('clickOnObject', e.detail);
                    }, false);

                    canvas.addEventListener('schemeDesigner.mouseOverObject', function (e) {
                        // console.log('mouseOverObject', e.detail);
                    }, false);

                    canvas.addEventListener('schemeDesigner.mouseLeaveObject', function (e) {
                        //  console.log('mouseLeaveObject', e.detail);
                    }, false);

                    canvas.addEventListener('schemeDesigner.scroll', function (e) {
                        //  console.log('scroll', e.detail);
                    }, false);

                    canvas.addEventListener('schemeDesigner.zoom', function (e) {
                        //  console.log('zoom', e.detail);
                    }, false);
                </script>

                <div class="field is-grouped">
                    <div class="control">
                        <button class="button is-link"  type="submit" name="submit" >Подтвердить бронь</button>
                    </div>
                </div>
                <script>
                    // получаем объект формы
                    var form = document.forms.user;
                    // прикрепляем обработчик кнопки
                    form.submit.addEventListener("click", sendRequest);
                     
                    // обработчик нажатия
                    function sendRequest(event){
                         
                        event.preventDefault();
                        var formData = new FormData(form);
                     
                        var request = new XMLHttpRequest();
                     
                        request.open("POST", form.action);
                         
                        request.onreadystatechange = function () {
                            if (request.readyState == 4 && request.status == 200)
                                document.getElementById("output").innerHTML=request.responseText;
                        }
                        request.send(formData);
                    }

                </script>
            </form>
        </div>
    </div>
@endsection
