<?php

use Symfony\Component\Translation\MessageCatalogue;

$catalogue = new MessageCatalogue('en', array (
  'imagecraft' => 
  array (
    'invalid.hex.color.%cp_invalid%.%example%' => '%cp_invalid% does not appear to be a valid hex color, here is an example: %example%.',
    'unexpected.argument.%cp_unexpected%.%expected%' => 'Expects argument to be %expected%, but received %cp_unexpected%.',
    'no.background.layer.added' => 'You haven\'t added a background layer yet.',
    'no.image.added' => 'You haven\'t added an image yet.',
    'no.font.added' => 'You haven\'t added a font yet.',
    'image.corrupted' => 'The image file is corrputed and cannot be opened.',
    'image.process.error' => 'An error encountered while processing image.',
    'text.adding.error' => 'An error encountered while adding text to image.',
    'unsupported.image.format.or.file.corrupted.%unsupported%.%supported%' => 'The format %unsupported% is either not supported or the file is damaged. Server supports %supported%.',
    'unknown.image.format.or.file.corrupted.%supported%' => 'The image is either in unknown format or corrupted. Server supports %supported%.',
    'adding.text.not.supported' => 'Adding text to image is not supported by server.',
    'gd.extension.not.enabled' => 'PHP GD extension is not enabled.',
    'output.image.format.not.supported.%cp_unsupported%' => 'The output image format %cp_unsupported% is not supported.',
    'output.image.dimensions.exceed.limit.%cp_dimensions%' => 'The output image dimensions (%cp_dimensions%) is bigger than the server allows.',
    'image.dimensions.exceed.limit.%cp_dimensions%' => 'The image dimensions (%cp_dimensions%) exceed the server allowable limit.',
    'not.enough.memory.to.process.image' => 'There is not enough memory to process the image.',
    'gif.parse.error' => 'An error occured while parsing GIF file.',
    'gif.animation.may.lost.due.to.corrupted.frame.data' => 'GIF animation may be lost. The frame data may be damaged or corrupted.',
    'gif.animation.may.lost.as.too.many.frames.or.dimensions.too.large.%total_frames%.%dimensions%' => 'GIF animation may be lost, as it either has too many frames (%total_frames%) or dimensions (%dimensions%) are too large.',
  ),
  'imc_stream' => 
  array (
    'file.not.found.or.access.denied.%cp_filename%' => 'The file %cp_filename% was not found or could not be accessed.',
    'php.setting.disabled.%setting%' => 'The PHP %setting% setting is disabled.',
    'network.resource.not.accessible.%cp_url%' => 'The network resource %cp_url% is not accessible.',
    'network.resource.exceeds.size.limit.%limit%' => 'The network resource exceeds the allowalbe size limit %limit%.',
    'file.read.error' => 'An error occured while reading the file.',
    'network.stream.read.timeout.%timeout%' => 'The timeout period %timeout% elapsed prior to completion of fetching network resource.',
  ),
));


return $catalogue;
