# ImageManipulate
ImageManipulate is a PHP class to manipulate images
this class can
Create wall with random file from media dir
Create horizontal stripe with random file from media dir
Create vertical stripe with random file from media dir
Create Ascii image with random file from media dir
Create resized image mantaining aspect ratio of image with random file from media dir
Crop to fit explicit width and height examples
Apply filter to image like:
			<p>IMG_FILTER_NEGATE: Reverses all colors of the image.</p>
			<p>IMG_FILTER_GRAYSCALE: Converts the image into grayscale.</p>
			<p>IMG_FILTER_BRIGHTNESS: Changes the brightness of the image. Use arg1 to set the level of brightness. The range for the brightness is -255 to 255.</p>
			<p>IMG_FILTER_CONTRAST: Changes the contrast of the image. Use arg1 to set the level of contrast.</p>
			<p>IMG_FILTER_COLORIZE: Like <p>IMG_FILTER_GRAYSCALE, except you can specify the color. Use arg1, arg2 and arg3 in the form of red, green, blue and arg4 for the alpha channel. The range for each color is 0 to 255.</p>
			<p>IMG_FILTER_EDGEDETECT: Uses edge detection to highlight the edges in the image.</p>
			<p>IMG_FILTER_EMBOSS: Embosses the image.</p>
			<p>IMG_FILTER_GAUSSIAN_BLUR: Blurs the image using the Gaussian method.</p>
			<p>IMG_FILTER_SELECTIVE_BLUR: Blurs the image.</p>
			<p>IMG_FILTER_MEAN_REMOVAL: Uses mean removal to achieve a "sketchy" effect.</p>
			<p>IMG_FILTER_SMOOTH: Makes the image smoother. Use arg1 to set the level of smoothness.</p>
			<p>IMG_FILTER_PIXELATE: Applies pixelation effect to the image, use arg1 to set the block size and arg2 to set the pixelation effect mode.</p>


