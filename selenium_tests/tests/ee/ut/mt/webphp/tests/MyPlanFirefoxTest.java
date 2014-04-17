package ee.ut.mt.webphp.tests;

import java.util.concurrent.TimeUnit;

import junit.framework.TestCase;

import org.junit.*;

import org.openqa.selenium.*;
import org.openqa.selenium.firefox.FirefoxDriver;

public class MyPlanFirefoxTest extends TestCase {
	private WebDriver driver;
	private String baseUrl;
	private StringBuffer verificationErrors = new StringBuffer();

	@Before
	public void setUp() throws Exception {
		driver = new FirefoxDriver();
		baseUrl = "http://regix.dev/";
		driver.manage().timeouts().implicitlyWait(30, TimeUnit.SECONDS);
	}

	@Test
	public void testMyPlan() throws Exception {
		driver.get(baseUrl + "login/logout");
		driver.get(baseUrl + "login/myplan");
		driver.findElement(By.id("login")).clear();
		driver.findElement(By.id("login")).sendKeys("tw1");
		driver.findElement(By.id("password")).clear();
		driver.findElement(By.id("password")).sendKeys("tw1");
		driver.findElement(By.name("submit")).click();
		assertTrue(isElementPresent(By.id("navigation_home")));
		assertTrue(isElementPresent(By.id("navigation_prev")));
		assertTrue(isElementPresent(By.id("navigation_now")));
		assertTrue(isElementPresent(By.id("navigation_next")));
		assertTrue(isElementPresent(By.id("time_slot_0")));
		assertTrue(isElementPresent(By.id("time_slot_1")));
		assertTrue(isElementPresent(By.id("time_slot_2")));
		assertTrue(isElementPresent(By.id("time_slot_3")));
		assertTrue(isElementPresent(By.id("time_slot_4")));
		assertTrue(isElementPresent(By.id("time_slot_5")));
		assertTrue(isElementPresent(By.id("time_slot_6")));
		assertTrue(isElementPresent(By.id("time_slot_7")));
		assertTrue(isElementPresent(By.id("time_slot_8")));
		assertTrue(isElementPresent(By.id("time_slot_9")));
		driver.get(baseUrl + "login/logout");
	}

	@After
	public void tearDown() throws Exception {
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