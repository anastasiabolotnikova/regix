package ee.ut.mt.webphp.tests;

import java.util.concurrent.TimeUnit;

import junit.framework.TestCase;

import org.junit.*;
import org.openqa.selenium.*;
import org.openqa.selenium.chrome.ChromeDriver;

public class SingInChromeTest extends TestCase {
	private WebDriver driver;
	private String baseUrl;
	private StringBuffer verificationErrors = new StringBuffer();

	@Before
	public void setUp() throws Exception {
		// Download chromedriver (http://code.google.com/p/chromedriver/downloads/list)
	    System.setProperty("webdriver.chrome.driver", "F:/Selenium/chromedriver.exe");
		driver = new ChromeDriver();
		baseUrl = "http://regix.dev/";
		driver.manage().timeouts().implicitlyWait(30, TimeUnit.SECONDS);
		addUsers();
	}
	
	public void addUsers() throws Exception {
		driver.get(baseUrl + "login/logout");
		
		// Register users
		driver.get(baseUrl + "reg");
	    driver.findElement(By.id("name")).clear();
	    driver.findElement(By.id("name")).sendKeys("singinTestName");
	    driver.findElement(By.id("username")).clear();
	    driver.findElement(By.id("username")).sendKeys("singinTest");
	    driver.findElement(By.id("password")).clear();
	    driver.findElement(By.id("password")).sendKeys("pass");
	    driver.findElement(By.id("repassword")).clear();
	    driver.findElement(By.id("repassword")).sendKeys("pass");
	    driver.findElement(By.id("email")).clear();
	    driver.findElement(By.id("email")).sendKeys("singintest");
	    driver.findElement(By.name("submit")).click();
	    
	}
	
	public void removeUsers() throws Exception {
	    
 	    // Login as admin
	    driver.get(baseUrl + "login");
	    driver.findElement(By.id("login")).clear();
	    driver.findElement(By.id("login")).sendKeys("test");
	    driver.findElement(By.id("password")).clear();
	    driver.findElement(By.id("password")).sendKeys("test_password");
	    driver.findElement(By.name("submit")).click();
	    
	    // Remove user
	    driver.get(baseUrl + "uac");
	    driver.findElement(By.xpath("//td/div[contains(text(), \"singinTestName\")]/../../td/div/a")).click();
	    
	}
	
	@Test
	public void testSingIn() throws Exception {

		// Sing In success: data is correct
	    driver.get(baseUrl + "login");
	    driver.findElement(By.id("login")).clear();
	    driver.findElement(By.id("login")).sendKeys("singinTest");
	    driver.findElement(By.id("password")).clear();
	    driver.findElement(By.id("password")).sendKeys("pass");
	    driver.findElement(By.name("submit")).click();

		assertTrue(isElementPresent(By.className("success_message_block")));
		
		// Sing In failure: no such user in the database
		driver.get(baseUrl + "login");
	    driver.findElement(By.id("login")).clear();
	    driver.findElement(By.id("login")).sendKeys("NoSuchUserInTheDataBase");
	    driver.findElement(By.id("password")).clear();
	    driver.findElement(By.id("password")).sendKeys("pass");
	    driver.findElement(By.name("submit")).click();

		assertTrue(isElementPresent(By.className("error_message_block")));
		
		// Sing In failure: wrong password
		driver.get(baseUrl + "login");
	    driver.findElement(By.id("login")).clear();
	    driver.findElement(By.id("login")).sendKeys("singinTest");
	    driver.findElement(By.id("password")).clear();
	    driver.findElement(By.id("password")).sendKeys("wrongpass");
	    driver.findElement(By.name("submit")).click();

		assertTrue(isElementPresent(By.className("error_message_block")));
	}

	@After
	public void tearDown() throws Exception {
		removeUsers();
		driver.quit();
		String verificationErrorString = verificationErrors.toString();
		if (!"".equals(verificationErrorString)) {
			fail(verificationErrorString);
		}
	}

	private boolean isElementPresent(By by) {
		try {
			driver.findElement(by);
			return true;
		} catch (NoSuchElementException e) {
			return false;
		}
	}
}